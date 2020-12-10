<?php

/**
 * Se creará un array que almacenará todos los datos necesarios para trabajar con el resto de elementos y las diferentes vistas de la aplicación y que permitirá mostrar los mensajes oportunos
 * 
 * @return array
 */
function msg() {
    $msg = [
        'msg' => [
            'info' => '',
            'error' => '',
            'conf' => ''
        ],
        'form' => '',
        'list' => '',
        'select' => [
            'clientes' => '',
            'empleados' => '',
            'citas' => ''
        ],
        'calendar' => [
            'add' => '',
            'update' => ''
        ],
        'horario' => [
            'add' => '',
            'update' => ''
        ],
        'marcas' => [
            'add' => [
                'seleccion' => '',
            ],
            'update' => [
                'seleccion' => '',
                'original' => '',
                'active' => '',
                'nuevaHora' => ''
            ]
        ],
        'otros' => [
            'all' => '',
            'action' => ''
        ]
    ];

    return $msg;
}
