<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div id="elfinder"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/elFinder/themes/Material/css/theme-gray.css?v='.get_app_version()); ?>">
<?php init_tail(); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.2/require.min.js"></script>
<script>
define('elFinderConfig', {
    defaultOpts: {
        url: '<?php echo $connector ?>',
        commandsOptions: {
            edit: {
                extraOptions: {
                    creativeCloudApiKey: '',
                    managerUrl: ''
                }
            },
            quicklook: {
                googleDocsMimes: [
                    'application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword',
                    'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ]
            }
        },
        bootCallback: function(fm, extraObj) {
            fm.bind('init', function() {
                // custom init
            });
            var title = document.title;
            fm.bind('open', function() {
                var path = '', cwd = fm.cwd();
                if (cwd) path = fm.path(cwd.hash) || null;
                document.title = path ? path + ':' + title : title;
            }).bind('destroy', function() {
                document.title = title;
            });
        }
    },
    managers: {
        'elfinder': {}
    }
});
define('returnVoid', void 0);

(function() {
    var elver = '<?php echo elFinder::getApiFullVersion()?>',
        jqver = '3.2.1',
        uiver = '1.12.1',
        start = function(elFinder, editors, config) {
            elFinder.prototype.loadCss('//cdnjs.cloudflare.com/ajax/libs/jqueryui/' + uiver + '/themes/smoothness/jquery-ui.css');

            $(function() {
                var elfEditorCustomData = {};
                if (typeof(csrfData) !== 'undefined') {
                    elfEditorCustomData[csrfData['token_name']] = csrfData['hash'];
                }

                var optEditors = {
                    commandsOptions: {
                        edit: {
                            editors: Array.isArray(editors) ? editors : []
                        }
                    }
                };

                var opts = {
                    height: 700,
                    customData: elfEditorCustomData,
                    getFileCallback: function(file) {
                        if (window.opener && typeof window.opener.elfinderCallback === 'function') {
                            window.opener.elfinderCallback(file.url);
                            window.close();
                        } else if (typeof window.elfinderCallback === 'function') {
                            window.elfinderCallback(file.url);
                            window.close();
                        } else {
                            console.warn('No elfinderCallback found.');
                        }
                    },
                    contextmenu: {
                        files: [
                            'getfile', '|', 'open', 'quicklook', '|', 'download', '|',
                            'copy', 'cut', 'paste', 'duplicate', '|',
                            'rm', '|', 'edit', 'rename', '|', 'archive', 'extract'
                        ]
                    },
                    ui: ['toolbar', 'tree', 'path', 'stat'],
                    uiOptions: {
                        toolbar: [
                            ['back', 'forward'],
                            ['mkdir', 'mkfile', 'upload'],
                            ['open', 'download', 'getfile'],
                            ['quicklook'],
                            ['copy', 'paste'],
                            ['rm'],
                            ['duplicate', 'rename', 'edit'],
                            ['extract', 'archive'],
                            ['search'],
                            ['view'],
                            ['info']
                        ]
                    }
                };

                if (config && config.managers) {
                    $.each(config.managers, function(id, mOpts) {
                        opts = Object.assign({}, config.defaultOpts || {}, opts);
                        try {
                            mOpts.commandsOptions.edit.editors = mOpts.commandsOptions.edit.editors.concat(editors || []);
                        } catch (e) {
                            Object.assign(mOpts, optEditors);
                        }
                        $('#' + id).elfinder(
                            $.extend(true, {
                                lang: '<?php echo get_media_locale($locale); ?>'
                            }, opts, mOpts || {}),
                            function(fm, extraObj) {
                                fm.bind('init', function() {});
                            }
                        );
                    });
                } else {
                    console.error('"elFinderConfig" object is wrong.');
                }
            });
        };

    var ie8 = (typeof window.addEventListener === 'undefined' && typeof document.getElementsByClassName === 'undefined');

    require.config({
        baseUrl: site_url + 'assets/plugins/elFinder/js',
        paths: {
            'jquery': '//cdnjs.cloudflare.com/ajax/libs/jquery/' + (ie8 ? '1.12.4' : jqver) + '/jquery.min',
            'jquery-ui': '//cdnjs.cloudflare.com/ajax/libs/jqueryui/' + uiver + '/jquery-ui.min',
            'elfinder': 'elfinder.min'
        },
        waitSeconds: 10
    });

    var load = function() {
        require(
            [
                'elfinder',
                'extras/editors.default',
                'elFinderConfig'
            ],
            start,
            function(error) {
                alert(error.message);
            }
        );
    };

    load();
})();
</script>

</body>
</html>
