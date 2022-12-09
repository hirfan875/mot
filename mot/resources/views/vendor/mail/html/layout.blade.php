<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="color-scheme" content="light"/>
        <meta name="supported-color-schemes" content="light"/>
        <style>
            @media only screen and (max-width: 600px) {
            .inner-body { width: 100%; }
            .footer { width: 100%; }
            }
            @media only screen and (max-width: 500px) {
            .button {width: 100%; }
            }
            body{background:#f4f4f4; font-family: 'Poppins', sans-serif; }
            .logo{ position: absolute; left: 20px; top: 20px; }
            .table_area td{ vertical-align: top; font-size: 14px;  }
            .header{ background:#E72128; position: relative; text-align:center; padding:60px 0 10px 0;  }
            .mainwrapper{ width:560px; border-radius:10px 10px 0 0; background:#fff; margin:30px auto 0 auto; padding:0 0 0px 0px;  }
            .footer{ margin:50px auto 0 auto; padding:30px 20px; overflow: hidden; background:#E7E7E7;  }
            .footer .left1{ float: left; width: 45%;  }
            .footer .right1{ float: right; width: 45%; text-align: right;  }
            .footer .right1 img{ width: 35px; margin:10px 10px 0 0 ;  }
            .footer p{ text-align:left ; margin: 0px;  }
            .main_title{ font-size:22px; font-weight:normal; text-align:center; color:#fff; background: #E72128; font-weight: 600; padding:20px 0 30px 0; margin:0px 0 20px 0;  }
            p{ font-size:14px; font-weight:normal; text-align:center; color:#4F4F4F; padding:0px; margin: 20px 12%;  }
            .link{ text-align: center;  }
            .link a{ color: #E72128; font-weight: 600;  }
            .primary_botton{ text-align: center; margin: 0 15%;  }
            .primary_botton a{ color: #fff; display: inline-block; width: 100%; padding:10px 0; text-decoration: none; margin: 20px 0; font-weight: 600; background: #E72128;  }
            .regards{ font-size:14px; line-height: 22px; font-weight:normal; text-align:center; color:#666; padding:20px 0 0 0; margin:40px 0 0px 0 ; text-align: center;  }
            .regards p{ font-size: 16px; color: #000;  }
            .alink{ color: #4b9dab; text-decoration: none;  }
            .order_number{ border-bottom:#ccc dashed  1px; margin:0 10%; font-weight: 600; color: #e72128; padding: 0 0 15px 0; overflow: hidden;  }
            .order_number .order{ float: left; width: 45%;  }
            .order_number .number{ float: right; width: 45%; text-align: right;  }
            .secondry_title{ text-align: center; font-size: 18px; margin: 30px 0 0 0; padding: 0;  }
            h3{ text-align: center; font-size: 15px; color: #4F4F4F; font-weight: 500; margin:0px 0 40px 0; padding: 0;  }
            h4{ text-align: center; font-size: 16px; color: #000; font-weight: 500; margin:0px 0 20px 0; padding: 0;  }
            .table_area{ margin: 0 6%; }
            .mt_top1{ margin-top: 20px; }
            .mt_top2{ margin-top:40px; }
            .mt_bottom{ margin-bottom: 20px; }
            .table_area strong{ color: #4F4F4F; font-weight: 400; }
            .table_area td{ vertical-align: top; font-size: 14px; }
            .table_area .align-right{ text-align: right; font-weight:500; color: #121212; width:65%;  }
            hr{ border-bottom: #ccc dashed 1px !important; margin: 30px 6% 20px 6%; border: 0; height: 0; background: transparent;  }
            .redMark{ color: red; }
            .table_container{ border: #ccc solid 1px; margin-right:6%; margin-left:6%; padding: 20px;  }
            .table_container .order_number{ margin: 0 0 20px 0;  }
            .table_container .table_area{ margin: 0 0%; }
            @media (max-width: 736px) {
                .mainwrapper{ width:100%; }
                .footer{ width:100%;  padding: 0; }
                .footer div{ margin:10px 0 15px 0 !important;  text-align:center !important; }
                .footer p{ width:100% !important; padding:0 !important; margin:15px 0  5px 0!important; text-align:center !important; }
                .mainwrapper div{ width:auto !important; }
                .primary_botton{ margin: 0; }
                .mainwrapper{ width: 100%; }
                .logo { position: relative; left: auto; top: auto; }
                .header{ padding: 30px 0 10px 0; }
                .primary_botton a { display: block; width: auto; padding: 10px 0; margin: 20px 5%; }
                .table_area .align-right{ width:60%; }
            }
        </style>
    </head>
    <body>
        <div  class="mainwrapper">
            {{ $header ?? '' }}

            <!--<h1 class="main_title">{{__(config('app.name'))}}</h1>-->
            {{ Illuminate\Mail\Markdown::parse($slot) }}

            {{ $subcopy ?? '' }}

            {{ $footer ?? '' }}
        </div>
    </body>
</html>
