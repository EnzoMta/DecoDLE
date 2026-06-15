<?php

namespace App\Enums;

enum GameType: string
{
    case CLASSIC = 'classic';
    case DESCRIPTION = 'description';
    case PHOTO = 'photo';
}
 // ICi on définit les 3 types de jeux possibles, j'ai pas mit le jeu de la map car j'ai aucne idée de comment la code
