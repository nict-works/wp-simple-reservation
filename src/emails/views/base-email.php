<?php

return function ($context): string {

    $title = $context['title'];
    $lang_code = $context['lang_code'];
    $content = $context['content'];

    return '
        <html>
            <head>
                <title>' . $title . '</title>

                <style>
                    * {
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;

                        font-family: Helvetica;
                        color: #444444;
                    }
                </style>
            </head>
            <body style="background: #fff; padding: 16px;">
                <a href="' . str_replace('/wp', '', site_url()) . '">
                    <figure style="margin: 0 0 16px 0; padding: 0; text-align: center;">
                        <img style="margin: 0; padding: 0;" src="https://dev.villagioiadivivere.com/app/themes/villagioiadivivere/assets/images/logo-villagioiadivivere-medium.png" width="160" height="184" />
                    </figure>
                </a>

                <div style="background: #faf8f6; padding: 24px; max-width: 640px; margin: 0 auto;">
                    <h1 style="margin: 0; padding: 0; color: #444444; font-size: 3rem; font-family: Montserrat-Thin; text-align: center; font-weight: lighter;">
                        ' . $title . '
                    </h1>

                    ' . $content . '
                </div>

                <div style="margin: 16px auto 0 auto; max-width: 640px;">
                    <p style="text-align: center; font-size: 0.8rem; color: #444444;">
                        ' . ($lang_code === 'nl' ? 'Deze e-mail is automatisch gegenereerd en is verzonden naar u door een actie die u heeft uitgevoerd op ' . get_bloginfo('name') . '. Afmelden voor deze e-mails is niet mogelijk. Neem contact op met de beheerder van ' . get_bloginfo('name') . ' voor meer informatie.' : 'This is an automated message. You receive this message because you have made a reservation on ' . get_bloginfo('name') . '. There is no way to unsubscribe for these update emails about your reservation. For more info please contact the owner of ' . get_bloginfo('name') . '.') . '
                    </p>
                </div>
            </body>
        </html>
    ';
};
