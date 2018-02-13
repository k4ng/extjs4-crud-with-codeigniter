<?php $base_url = "http://k4ng.cahya/extjsinternal/ext-codeigniter/index.php/extjs"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Log Akses - kang cahya</title>
        <link rel="stylesheet" type="text/css" href="../ext/resources/css/ext-all.css" />
        <script type="text/javascript" src="../ext/ext-all.js"></script>
        <script type="text/javascript">
            Ext.onReady(function () {
                //Buat Form Untuk isian
                var frmGrid = Ext.create('Ext.form.Panel', {
                    bodyPadding: 5,
                    frame: true,
                    items: [{
                            xtype: 'hidden',
                            name: 'log_id'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'IP',
                            name: 'log_ip',
                            value: "<?= $_SERVER['REMOTE_ADDR']; ?>",
                            readonly: true
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'OS',
                            name: 'log_os'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Browser',
                            name: 'log_browser'
                        }, {
                            xtype: 'radiogroup',
                            items: [
                                {boxLabel: 'Access', name: 'log_type', inputValue: 'access', checked: true},
                                {boxLabel: 'Activity', name: 'log_type', inputValue: 'activity'},
                                {boxLabel: 'Modify', name: 'log_type', inputValue: 'modify'}
                            ],
                            fieldLabel: 'Type',
                        }, {
                            xtype: 'combobox',
                            store: Ext.create('Ext.data.Store', {
                                fields: ['valx', 'name'],
                                data: [
                                    {"valx": "login", "name": "login"},
                                    {"valx": "logout", "name": "logout"},
                                    {"valx": "timeout", "name": "timeout"},
                                    {"valx": "failed_in", "name": "failed_in"},
                                    {"valx": "hack", "name": "hack"}
                                ]
                            }),
                            queryMode: 'local',
                            displayField: 'name',
                            valueField: 'valx',
                            fieldLabel: 'Status',
                            name: 'log_status'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Time',
                            name: 'log_time'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Email',
                            name: 'log_email'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Password',
                            name: 'log_password'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'URL',
                            name: 'log_url'
                        }]
                });

                //Buat object Windows
                var winGrid = Ext.create('widget.window', {
                    title: 'Tambah Data',
                    width: 400,
                    height: 350,
                    modal: true,
                    closeAction: 'hide',
                    items: frmGrid,
                    layout: 'fit',
                    bodyPadding: 5,
                    buttons: [{
                            text: 'OK',
                            handler: function () {
                                frmGrid.el.mask('Saving data ...', 'x-mask-loading');
                                frmGrid.getForm().submit({
                                    method: 'POST',
                                    url: '<?= $base_url; ?>/save',
                                    success: function (form, action) {
                                        frmGrid.el.unmask();
                                        storeGrid.loadPage(1);
                                        winGrid.hide();
                                    }, failure: function (form, action) {
                                        frmGrid.el.unmask();
                                        var res = Ext.decode(action.response.responseText);
                                        Ext.Msg.alert('Informasi!', res.responseText);
                                    }
                                });
                            }
                        }, {
                            text: 'Cancel',
                            handler: function () {
                                winGrid.hide();
                            }
                        }]
                });

                Ext.define('treeMenu', {
                    extend: 'Ext.data.Model',
                    fields: ['log_id', 'log_ip', 'log_os', 'log_browser', 'log_type', 'log_status', 'log_time', 'log_email', 'log_password', 'log_url']
                });

                var storeGrid = Ext.create('Ext.data.Store', {
                    model: 'treeMenu',
                    proxy: {
                        type: 'ajax',
                        url: '<?= $base_url; ?>',
                        noCache: false,
                        params: {
                            start: 0,
                            limit: 20
                        },
                        actionMethods: 'GET',
                        reader: {
                            type: 'json',
                            root: 'response',
                            totalProperty: 'jumlah',
                            idProperty: 'log_id'
                        }
                    },
                    pageSize: 20,
                    autoLoad: true,
                    sorters: [{
                            property: 'log_id',
                            direction: 'ASC'
                        }]
                });

                var smGrid = Ext.create('Ext.selection.CheckboxModel');
                // create the Grid
                var grid = Ext.create('Ext.grid.Panel', {
                    store: storeGrid,
                    columns: [
                        {header: 'Log Id', width: 50, sortable: true, dataIndex: 'log_id'},
                        {header: 'Log Ip', width: 50, sortable: true, dataIndex: 'log_ip'},
                        {header: 'Log Os', width: 100, sortable: true, dataIndex: 'log_os'},
                        {header: 'Log Browser', width: 100, sortable: true, dataIndex: 'log_browser'},
                        {header: 'Log Type', width: 100, sortable: true, dataIndex: 'log_type'},
                        {header: 'Log Status', width: 100, sortable: true, dataIndex: 'log_status'},
                        {header: 'Log Time', width: 100, sortable: true, dataIndex: 'log_time'},
                        {header: 'Log Email', width: 100, sortable: true, dataIndex: 'log_email'},
                        {header: 'Log Password', width: 150, sortable: true, dataIndex: 'log_password'},
                        {header: 'Log URL', width: 250, sortable: true, dataIndex: 'log_url'}
                    ],
                    height: 450,
                    selModel: smGrid,
                    width: 1200,
                    title: 'Log Access',
                    renderTo: 'grid-view',
                    viewConfig: {
                        stripeRows: true
                    },
                    tbar: [{
                            text: 'Tambah',
                            handler: function () {
                                frmGrid.getForm().reset();
                                winGrid.show();
                            }
                        },
                        '-',
                        {
                            text: 'Ubah',
                            disabled: true,
                            id: 'ubah',
                            handler: function () {
                                var sel = grid.getSelectionModel();

                                if (sel.lastSelected) {
                                    frmGrid.getForm().loadRecord(sel.lastSelected);
                                    winGrid.show();
                                } else {
                                    Ext.MessageBox.alert('Information', 'Pilih data yang akan diedit.');
                                }
                            }
                        },
                        '-',
                        {
                            text: 'Hapus',
                            disabled: true,
                            id: "hapus",
                            handler: function () {
                                Ext.MessageBox.confirm('Information', 'Anda yakin akan menghapus data ini ?', deleteGrid);
                            },
                        }],
                    bbar: new Ext.PagingToolbar({
                        store: storeGrid,
                        displayInfo: true,
                        displayMsg: 'Data yang ada {0} - {1} Dari {2}',
                        emptyMsg: "Tidak ada data"
                    }),
                    listeners: {
                        selectionchange: function () {
                            console.log("testing");
                            var hapus = Ext.getCmp("hapus");
                            var ubah = Ext.getCmp("ubah");

                            if (hapus.isDisabled()) {
                                hapus.setDisabled(false);
                            } else {
                                hapus.setDisabled(true);
                            }

                            if (ubah.isDisabled()) {
                                ubah.setDisabled(false);
                            } else {
                                ubah.setDisabled(true);
                            }
                        }
                    }
                });

                var deleteGrid = function (btn) {
                    if (btn == 'yes') {
                        var s = grid.getSelectionModel().selected.items;

                        var id_del = "";
                        for (var i = 0, r; r = s[i]; i++) {
                            id_del = id_del + ';' + r.data.log_id;
                        }
                        ;
                        Ext.Ajax.request({
                            waitMsg: 'Delete ...',
                            url: '<?= $base_url; ?>/delete',
                            params: {
                                id: id_del
                            },
                            success: function (response, options) {
                                frmGrid.getForm().reset();
                                storeGrid.loadPage(1);
                            },
                            failure: function (response, options) {
                                var rsp = Ext.decode(response.responseText);
                                Ext.MessageBox.alert('Delete', rsp.responseText);
                            }
                        });
                    }
                }
            });
        </script>
    </head>
    <body>
        <div id="grid-view"></div>
    </body>
</html>
