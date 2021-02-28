
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Not Found</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 50vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .code {
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }
        </style>
    </head>
    <body>

        <div style="margin-top:5em;">
            <center>
            <a href="{{ config('app.url') }}" rel="noopener noreferrer" style="text-decoration:none" target="_blank" data-saferedirecturl=""><img id="m_5327504558924647655OWATemporaryImageDivContainer1" src="{{ url('images/logo.png') }}" alt="" border="0" width="250" style="max-width:250px;display:block;width:174px;border-radius: 50%;" class="CToWUd"></a>

            <br> <br>
            <div class="code">
                @if(\Request::get('locale') == 'fr')
                    Une erreur s'est produite
                @else
                    An error has occurred
                @endif
            </div>
            <br> <br> <br>

            <table cellpadding="0" cellspacing="0" border="0" role="presentation" class="m_5327504558924647655btn_table" style="display:table">
                <tbody>
                    <tr>
                    
                    @if(\Request::get('locale') == 'fr')  
                    <td class="m_5327504558924647655btn_td" bgcolor="#000000" style="font-family:Arial;font-size:18px;color:#ffffff;padding-top:16px;padding-right:12px;padding-bottom:16px;padding-left:12px;text-align:center;text-decoration:none;border-radius:6px;text-overflow:ellipsis;overflow:hidden;word-wrap:break-word;word-break:normal;font-weight:bold;font-style:normal;line-height:17px;border-top-width:12px;display:inline-block;padding: 1em 2.4em;">
                        <a href="{{ route('fr.checkout.page') }}" rel="noopener noreferrer" style="color:#ffffff;text-decoration:none" >
                            Lelancer la commande
                        </a>
                    </td>
                    @else
                    <td class="m_5327504558924647655btn_td" bgcolor="#000000" style="font-family:Arial;font-size:18px;color:#ffffff;padding-top:16px;padding-right:12px;padding-bottom:16px;padding-left:12px;text-align:center;text-decoration:none;border-radius:6px;text-overflow:ellipsis;overflow:hidden;word-wrap:break-word;word-break:normal;font-weight:bold;font-style:normal;line-height:17px;border-top-width:12px;display:inline-block;padding: 1em 2.4em;">
                        <a href="{{ route('fr.checkout.page') }}" rel="noopener noreferrer" style="color:#ffffff;text-decoration:none" >
                            Launch the command
                        </a>
                    </td>
                    @endif
                    </tr>
                </tbody>
            </table>

            <table cellpadding="0" cellspacing="0" border="0" role="presentation" class="m_5327504558924647655btn_table" style="display:table;">
                <tbody>
                    <tr>

                    @if(\Request::get('locale') == 'fr')  
                        <td class="m_5327504558924647655btn_td" bgcolor="#2abebc" style="margin-top: .5em; font-family:Arial;font-size:18px;color:#ffffff;padding-top:16px;padding-right:12px;padding-bottom:16px;padding-left:12px;text-align:center;text-decoration:none;border-radius:6px;text-overflow:ellipsis;overflow:hidden;word-wrap:break-word;word-break:normal;font-weight:bold;font-style:normal;line-height:17px;border-top-width:12px;display:inline-block; padding: 1em .9em;">
                            <a href="/" rel="noopener noreferrer" style="color:#ffffff;text-decoration:none" >
                                Retour vers la page d'accueil
                            </a>
                        </td>
                    @else
                        <td class="m_5327504558924647655btn_td" bgcolor="#2abebc" style="margin-top: .5em; font-family:Arial;font-size:18px;color:#ffffff;padding-top:16px;padding-right:12px;padding-bottom:16px;padding-left:12px;text-align:center;text-decoration:none;border-radius:6px;text-overflow:ellipsis;overflow:hidden;word-wrap:break-word;word-break:normal;font-weight:bold;font-style:normal;line-height:17px;border-top-width:12px;display:inline-block; padding: 1em .9em;">
                            <a href="/" rel="noopener noreferrer" style="color:#ffffff;text-decoration:none" >
                                Back to the home page
                            </a>
                        </td>
                    @endif
                    </tr>
                </tbody>
            </table>
        </center>
        </div>
    </body>
</html>
