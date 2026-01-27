<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function getMenuGroups()
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $dashboardPath = '/dashboard';

        if ($user->hasRole(['super_admin', 'admin_up3', 'admin_ulp', 'verifikator'])) {
            $dashboardPath = '/admin/dashboard';
        }

        $menuStructure = [
            [
                'title' => 'MAIN MENU',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'icon' => 'dashboard',
                        'path' => $dashboardPath,
                        'permission' => 'dashboard'
                    ],
                ],
            ],
            [
                'title' => 'ENTRY DATA',
                'items' => [
                    [
                        'name' => 'Entry PJU',
                        'icon' => 'plus',
                        'path' => '/pju/create',
                        'permission' => 'pju_create'
                    ],
                    [
                        'name' => 'Entry Trafo',
                        'icon' => 'plus',
                        'path' => '/trafo/create',
                        'permission' => 'trafo_create'
                    ],
                ],
            ],
            [
                'title' => 'MASTER DATA',
                'items' => [
                    [
                        'name' => 'Data Table',
                        'icon' => 'list',
                        'path' => '#',
                        'subItems' => [
                            ['name' => 'Data LPJU', 'path' => '/pju', 'permission' => 'pju_view'],
                            ['name' => 'Data Trafo', 'path' => '/trafo', 'permission' => 'trafo_view'],
                            ['name' => 'Verifikasi', 'path' => '/pju/verification', 'permission' => 'verifikator_edit'],
                        ]
                    ],
                    [
                        'name' => 'Data Photo',
                        'icon' => 'image',
                        'path' => '#',
                        'subItems' => [
                            ['name' => 'Photo LPJU', 'path' => '/pju/gallery', 'permission' => 'pju_gallery'],
                            ['name' => 'Photo Trafo', 'path' => '/trafo/gallery', 'permission' => 'trafo_gallery'],
                        ]
                    ],
                    [
                        'name' => 'Data Maps',
                        'icon' => 'map',
                        'path' => '#',
                        'permission' => 'maps_view',
                        'subItems' => [
                            ['name' => 'Peta Keseluruhan', 'path' => '/maps/sebaran'],
                            ['name' => 'Peta Kec/Kelurahan', 'path' => '/maps/area'],
                            ['name' => 'Peta IDPEL', 'path' => '/maps/idpel'],
                        ]
                    ],
                ],
            ],
            [
                'title' => 'REPORT',
                'items' => [
                    [
                        'name' => 'Laporan',
                        'icon' => 'clipboard',
                        'path' => '#',
                        'permission' => 'report_view',
                        'subItems' => [
                            ['name' => 'Laporan PJU dan IDPEL', 'path' => '/pju/meterisasi'],
                            ['name' => 'Laporan Visual PJU', 'path' => '/pju/visual'],
                            ['name' => 'Laporan Jenis Lampu', 'path' => '/pju/rekap-jenis'],
                            ['name' => 'Laporan Realisasi', 'path' => '/pju/realisasi'],
                            ['name' => 'Laporan Harian', 'path' => '/pju/rekap-harian'],
                            ['name' => 'Laporan Keseluruhan', 'path' => '/pju/rekap-total'],
                            ['name' => 'Hasil Data Petugas', 'path' => '/pju/officers'],
                        ]
                    ],
                ],
            ],
            [
                'title' => 'ADMINISTRATOR',
                'items' => [
                    [
                        'name' => 'User Management',
                        'icon' => 'users',
                        'path' => '#',
                        'permission' => 'user_view',
                        'subItems' => [
                            ['name' => 'Daftar Pengguna', 'path' => '/users', 'permission' => 'user_view'],
                            ['name' => 'Roles & Permission', 'path' => '/roles', 'permission' => 'user_edit'],
                        ]
                    ],
                ],
            ]
        ];

        $filteredMenu = [];

        foreach ($menuStructure as $group) {
            $filteredItems = [];

            foreach ($group['items'] as $item) {
                // Cek permission item utama
                if (isset($item['permission']) && !$user->can($item['permission'])) {
                    continue;
                }

                // Cek subItems (jika ada)
                if (isset($item['subItems'])) {
                    $filteredSubItems = [];
                    foreach ($item['subItems'] as $subItem) {
                        // Jika subItem punya permission spesifik, cek juga
                        // Jika tidak punya, ikut permission induk (yang sudah lolos di atas)
                        if (isset($subItem['permission']) && !$user->can($subItem['permission'])) {
                            continue;
                        }
                        $filteredSubItems[] = $subItem;
                    }

                    // Jika setelah difilter subItems kosong, dan item ini hanya container (path '#'),
                    // maka item ini tidak perlu ditampilkan.
                    if (empty($filteredSubItems) && $item['path'] === '#') {
                        continue;
                    }

                    $item['subItems'] = $filteredSubItems;
                }

                $filteredItems[] = $item;
            }

            // Hanya tambahkan Group jika memiliki minimal 1 item
            if (!empty($filteredItems)) {
                $group['items'] = $filteredItems;
                $filteredMenu[] = $group;
            }
        }

        // Tambahkan Menu Pengaturan (Profile) untuk semua user di paling bawah
        // $filteredMenu[] = [
        //     'title' => 'PENGATURAN',
        //     'items' => [
        //         [
        //             'name' => 'Profil Saya',
        //             'icon' => 'user-profile',
        //             'path' => '/profile',
        //         ],
        //     ]
        // ];

        return $filteredMenu;
    }

    public static function isActive($path)
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',
            'user-profile' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z" fill="currentColor"></path></svg>',
            'map' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C7.58172 2 4 5.58172 4 10C4 14.4183 12 22 12 22C12 22 20 14.4183 20 10C20 5.58172 16.4183 2 12 2ZM12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z" fill=""/></svg>',
            'users' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M16 8C16 10.21 14.21 12 12 12C9.79 12 8 10.21 8 8C8 5.79 9.79 4 12 4C14.21 4 16 5.79 16 8ZM4 18C4 15.34 9.33 14 12 14C14.67 14 20 15.34 20 18V20H4V18Z" fill=""/></svg>',
            'list' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6H20V8H4V6ZM4 11H20V13H4V11ZM4 16H20V18H4V16Z" fill=""/></svg>',
            'plus' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'clipboard' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 2h6a2 2 0 012 2v2H7V4a2 2 0 012-2zM5 6h14v16a2 2 0 01-2 2H7a2 2 0 01-2-2V6z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'image' => '<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/><path d="M21 15l-5-5L5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}