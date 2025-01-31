<?php

namespace App\Enums;

enum HandleType: string
{
    case Facebook = 'facebook';
    case Insta = 'instagram';
    case X = 'x';
    case Discord = 'discord';
}
