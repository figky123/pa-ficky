<?php

// app/Helpers/HelperFunctions.php

function getKategoriColor($kategori)
{
    switch ($kategori) {
        case 'sangat buruk':
            return '#FF0000'; // Merah
            break;
        case 'buruk':
            return '#FFA500'; // Jingga
            break;
        case 'sedang':
            return '#FFFF00'; // Kuning
            break;
        case 'baik':
            return '#00FF00'; // Hijau
            break;
        default:
            return '#000000'; // Hitam sebagai default
    }
}
