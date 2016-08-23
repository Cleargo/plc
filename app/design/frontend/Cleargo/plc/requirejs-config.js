var config = {
    "paths": {
        "jquery.fancybox":"js/fancybox/jquery.fancybox",
        "jquery.mCustomScrollbar":"js/mCustomScrollbar/jquery.mCustomScrollbar.concat.min",
        "jquery.touchwipe":"js/jquery.touchwipe.1.1.1",
        "plc.fn":"js/plc.fn"
    },
    "shim": {
        "jquery.fancybox":{
            "deps":['jquery']
        },
        "jquery.mCustomScrollbar":{
            "deps":['jquery']
        },
        "jquery.touchwipe":{
            "deps":['jquery']
        },
        "plc.fn":{
            "deps":['jquery']
        }
    }
};