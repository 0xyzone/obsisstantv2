<?php

namespace App\Enums;

enum TournamentType: string
{
    case Team = "team";
    case Solo = "solo";
    case FFA = "ffa";
}
