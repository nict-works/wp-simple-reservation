<?php

return function ($context): string {
    $body = $context['body'];

    return '
        <p style="text-align: center; color: #444444; font-size: 1rem;">
            ' . $body . '
        </p>
    ';
};
