var config = {
    "paths": {
        "jquery.fancybox":"js/fancybox/jquery.fancybox",
        "jquery.mCustomScrollbar":"js/mCustomScrollbar/jquery.mCustomScrollbar.concat.min",
        "jquery.touchwipe":"js/jquery.touchwipe.1.1.1",
        "bootstrap-multiselect":"js/bootstrap-multiselect",
        "bootstrap":"js/bootstrap.min",
        "plc.fn":"js/plc.fn"
    },
    "shim": {
        "bootstrap":{
            "deps":['jquery']
        },
        "jquery.fancybox":{
            "deps":['jquery']
        },
        "jquery.mCustomScrollbar":{
            "deps":['jquery']
        },
        "jquery.touchwipe":{
            "deps":['jquery']
        },
        "bootstrap-multiselect":{
            "deps":['jquery',"bootstrap"]
        },
        "plc.fn":{
            "deps":['jquery']
        }
    }
};