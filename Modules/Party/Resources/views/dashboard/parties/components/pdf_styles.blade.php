<link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet" type="text/css"/>
<link href="{{asset('/admin/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('/admin/assets/global/plugins/bootstrap/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css"/>
<style>
    @media print,screen {
        * {
            color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .pie:before {
            background: conic-gradient(#23366e calc({{ (($data['package_limit'] - $data['remaining_invitations'])/$data['package_limit']) * 100  }}*1%),#f0f0f0 0) !important;
            print-color-adjust: exact;
        }

        body{font-family: 'Cairo',Sans-Serif;-webkit-print-color-adjust: exact;}
        .card{padding: 10px 20px;border: 2px solid #ccc ;border-radius: 5px;margin-bottom: 15px;color: #FFF !important;}
        .card h3{color: #FFF !important;}
        .card p{font-weight: bold;font-size: 18px;color: #FFF !important;}
        .limit{color: #daa727 !important;border: 0; print-color-adjust: exact; }
        .u-border-2{color:#333 !important;}
        .remaining{background: #daa727 !important; print-color-adjust: exact; }
        .accepted{background: #61c23e !important; print-color-adjust: exact; }
        .rejected{background: #c23434 !important; print-color-adjust: exact; }
        .attended{background: #ebb939 !important; print-color-adjust: exact; }
        .pending{background: #bfbfbf !important; print-color-adjust: exact; }
        .active{background: #4d78e7 !important; print-color-adjust: exact; }
        .u-section-2{width:100%;}
        .stats-icon{display: block;width: 70px;height: 70px;margin-top: -40px;border: 3px solid #fff !important;border-radius: 50%;color: #FFF !important;}
        .stats-icon i{padding:10px;font-size: 40px;display:block;width: auto;color: #FFF !important;}
        .stats-icon i:before{color: #FFF !important;}
        .badge{background-color: #777 !important;color: #FFF !important;}
        .pie {
            width: 150px;
            aspect-ratio: 1;
            position: relative;
            display: inline-grid;
            place-content: center;
            margin: 5px;
            font-size: 25px;
            font-weight: bold;
            font-family: sans-serif;
        }
        .pie:before {
            content: "";
            position: absolute;
            border-radius: 50%;
            inset: 0;
            /*-webkit-mask:radial-gradient(farthest-side,#0000 calc(99% - 15px),#000 calc(100% - 15px));*/
            mask:radial-gradient(farthest-side,#0000 calc(99% - 15px),#000 calc(100% - 15px)) !important;
            print-color-adjust: exact;
        }
        table{border:1px solid #ddd !important;}
        th,td{text-align: center;}
        .invite_row{border:1px solid #ddd !important;padding: 10px;margin: 0;margin-bottom: 5px;}
        .invite_row .col-xs-4{text-align: center;}
        html,*{direction: rtl}
    }
</style>
