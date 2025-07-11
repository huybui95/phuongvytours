<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->load->model('invoices_supplier_model');

return App_table::find('invoices_supplier')
    ->outputUsing(function ($params) {
        extract($params);
        $aColumns = [
            'number',
            'total',
            'total_tax',
            'YEAR(date) as year',
            'date',
            get_sql_select_client_company(),
            db_prefix() . 'projects.name as project_name',
            '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'invoices_supplier.id and rel_type="invoice" ORDER by tag_order ASC) as tags',
            'duedate',
            db_prefix() . 'invoices_supplier.status',
        ];

        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'invoices_supplier';

        $join = [
            'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices_supplier.clientid',
            'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices_supplier.currency',
            'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'invoices_supplier.project_id',
        ];

        $custom_fields = get_table_custom_fields('invoice');

        foreach ($custom_fields as $key => $field) {
            $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

            array_push($customFieldsColumns, $selectAs);
            array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
            array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'invoices_supplier.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
        }

        $where = [];

        if ($filtersWhere = $this->getWhereFromRules()) {
            $where[] = $filtersWhere;
        }

        if ($clientid != '') {
            array_push($where, 'AND ' . db_prefix() . 'invoices_supplier.clientid=' . $this->ci->db->escape_str($clientid));
        }

        if ($project_id) {
            array_push($where, 'AND project_id=' . $this->ci->db->escape_str($project_id));
        }
        $aColumns = hooks()->apply_filters('invoices_supplier_table_sql_columns', $aColumns);

        // Fix for big queries. Some hosting have max_join_limit
        if (count($custom_fields) > 4) {
            @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
        }

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
            db_prefix() . 'invoices_supplier.id',
            db_prefix() . 'invoices_supplier.clientid',
            db_prefix() . 'currencies.name as currency_name',
            'formatted_number',
            'project_id',
            'hash',
            'recurring',
            'deleted_customer_name',
        ]);
        $output  = $result['output'];
        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {
            $formattedNumber = format_invoice_number($aRow['id']);

            if(empty($aRow['formatted_number']) || $formattedNumber !== $aRow['formatted_number']) {
                $this->ci->invoices_supplier_model->save_formatted_number($aRow['id']);
            }

            $row = [];

            $numberOutput = '';

            // If is from client area table
            if (is_numeric($clientid) || $project_id) {
                $numberOutput = '<a href="' . admin_url('invoices_supplier/list_invoices_supplier/' . $aRow['id']) . '" target="_blank" class="tw-font-medium">' . e($formattedNumber) . '</a>';
            } else {
                $numberOutput = '<a href="' . admin_url('invoices_supplier/list_invoices_supplier/' . $aRow['id']) . '" onclick="init_invoice(' . $aRow['id'] . '); return false;" class="tw-font-medium">' . e($formattedNumber) . '</a>';
            }

            if ($aRow['recurring'] > 0) {
                $numberOutput .= '<br /><span class="label label-primary inline-block tw-mt-1 tw-font-medium"> ' . _l('invoice_recurring_indicator') . '</span>';
            }

            $numberOutput .= '<div class="row-options">';

            $numberOutput .= '<a href="' . site_url('invoice/' . $aRow['id'] . '/' . $aRow['hash']) . '" target="_blank">' . _l('view') . '</a>';
            if (staff_can('edit', 'invoices_supplier')) {
                $numberOutput .= ' | <a href="' . admin_url('invoices_supplier/invoice/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }
            $numberOutput .= '</div>';

            $row[] = $numberOutput;

            $row[] = '<span class="tw-font-medium">' . e(app_format_money($aRow['total'], $aRow['currency_name'])) . '</span>';

            $row[] = '<span class="tw-font-medium">' . e(app_format_money($aRow['total_tax'], $aRow['currency_name'])) . '</span>';

            $row[] = e($aRow['year']);

            $row[] = e(_d($aRow['date']));

            if (empty($aRow['deleted_customer_name'])) {
                $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . e($aRow['company']) . '</a>';
            } else {
                $row[] = e($aRow['deleted_customer_name']);
            }

            $row[] = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '">' . e($aRow['project_name']) . '</a>';

            $row[] = render_tags($aRow['tags']);

            $row[] = e(_d($aRow['duedate']));

            $row[] = format_invoice_status($aRow[db_prefix() . 'invoices_supplier.status']);

            // Custom fields add values
            foreach ($customFieldsColumns as $customFieldColumn) {
                $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
            }

            $row['DT_RowClass'] = 'has-row-options';

            $row = hooks()->apply_filters('invoices_supplier_table_row_data', $row, $aRow);

            $output['aaData'][] = $row;
        }

        return $output;
    })->setRules([
        App_table_filter::new('number', 'NumberRule')->label(_l('invoice_add_edit_number')),
        App_table_filter::new('total', 'NumberRule')->label(_l('invoice_total')),
        App_table_filter::new('subtotal', 'NumberRule')->label(_l('invoice_subtotal')),
        App_table_filter::new('date', 'DateRule')->label(_l('invoice_add_edit_date')),
        App_table_filter::new('duedate', 'DateRule')
            ->label(_l('invoice_dt_table_heading_duedate'))
            ->withEmptyOperators(),
        App_table_filter::new('sent', 'BooleanRule')->label(_l('estimate_status_sent'))->raw(function ($value) {
            if ($value == '1') {
                return 'sent = 1';
            }

            return 'sent = 0 and ' . db_prefix() . 'invoices_supplier.status NOT IN (' . invoices_supplier_model::STATUS_PAID . ',' . invoices_supplier_model::STATUS_CANCELLED . ')';
        }),
        App_table_filter::new('sale_agent', 'SelectRule')->label(_l('sale_agent_string'))
            ->withEmptyOperators()
            ->emptyOperatorValue(0)
            ->isVisible(fn () => staff_can('view', 'invoices_supplier'))
            ->options(function ($ci) {
                return collect($ci->invoices_supplier_model->get_sale_agents())->map(function ($data) {
                    return [
                        'value' => $data['sale_agent'],
                        'label' => get_staff_full_name($data['sale_agent']),
                    ];
                })->all();
            }),

        App_table_filter::new('status', 'MultiSelectRule')
            ->label(_l('invoice_dt_table_heading_status'))
            ->options(function ($ci) {
                return collect($ci->invoices_supplier_model->get_statuses())->map(fn ($status) => [
                    'value' => (string) $status,
                    'label' => format_invoice_status($status, '', false),
                ])->all();
            }),

        App_table_filter::new('year', 'MultiSelectRule')
            ->label(_l('year'))
            ->raw(function ($value, $operator) {
                if ($operator == 'in') {
                    return 'YEAR(date) IN (' . implode(',', $value) . ')';
                }

                return 'YEAR(date) NOT IN (' . implode(',', $value) . ')';
            })
            ->options(function ($ci) {
                return collect($ci->invoices_supplier_model->get_invoices_supplier_years())->map(fn ($data) => [
                    'value' => $data['year'],
                    'label' => $data['year'],
                ])->all();
            }),
        App_table_filter::new('recurring', 'BooleanRule')->label(_l('invoices_supplier_list_recurring'))->raw(function ($value) {
            return $value == '1' ? 'recurring > 0' : 'recurring = 0';
        }),
        App_table_filter::new('not_have_payment', 'BooleanRule')->label(_l('invoices_supplier_list_not_have_payment'))->raw(function ($value) {
            return '(' . db_prefix() . 'invoices_supplier.id ' . ($value == '1' ? 'NOT IN' : 'IN') . ' (SELECT invoiceid FROM ' . db_prefix() . 'invoicepaymentrecords UNION ALL SELECT invoice_id FROM ' . db_prefix() . 'credits) AND ' . db_prefix() . 'invoices_supplier.status != ' . invoices_supplier_model::STATUS_CANCELLED . ')';
        }),
        App_table_filter::new('made_payment_by', 'MultiSelectRule')->label(str_replace(' %s', '', _l('invoices_supplier_list_made_payment_by')))->options(function ($ci) {
            return collect($ci->payment_modes_model->get('', [], true))->map(fn ($mode) => [
                'value' => $mode['id'],
                'label' => $mode['name'],
            ])->all();
        })->raw(function ($value, $operator, $sqlOperator) {
            $dbPrefix    = db_prefix();
            $sqlOperator = $sqlOperator['operator'];

            return "({$dbPrefix}invoices_supplier.id IN (SELECT invoiceid FROM {$dbPrefix}invoicepaymentrecords WHERE paymentmode {$sqlOperator} ('" . implode("','", $value) . "')))";
        }),
    ]);
