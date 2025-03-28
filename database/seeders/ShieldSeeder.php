<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_announcement","view_any_announcement","create_announcement","update_announcement","restore_announcement","restore_any_announcement","replicate_announcement","reorder_announcement","delete_announcement","delete_any_announcement","force_delete_announcement","force_delete_any_announcement","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","page_MyProfilePage","view_game","view_any_game","create_game","update_game","restore_game","restore_any_game","replicate_game","reorder_game","delete_game","delete_any_game","force_delete_game","force_delete_any_game","view_game::hero","view_any_game::hero","create_game::hero","update_game::hero","restore_game::hero","restore_any_game::hero","replicate_game::hero","reorder_game::hero","delete_game::hero","delete_any_game::hero","force_delete_game::hero","force_delete_any_game::hero","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_GeneralSettingsPage"]},{"name":"panel_user","guard_name":"web","permissions":[]}]';
        $directPermissions = '{"12":{"name":"view_match::making","guard_name":"web"},"13":{"name":"view_any_match::making","guard_name":"web"},"14":{"name":"create_match::making","guard_name":"web"},"15":{"name":"update_match::making","guard_name":"web"},"16":{"name":"restore_match::making","guard_name":"web"},"17":{"name":"restore_any_match::making","guard_name":"web"},"18":{"name":"replicate_match::making","guard_name":"web"},"19":{"name":"reorder_match::making","guard_name":"web"},"20":{"name":"delete_match::making","guard_name":"web"},"21":{"name":"delete_any_match::making","guard_name":"web"},"22":{"name":"force_delete_match::making","guard_name":"web"},"23":{"name":"force_delete_any_match::making","guard_name":"web"},"30":{"name":"view_tournament::team","guard_name":"web"},"31":{"name":"view_any_tournament::team","guard_name":"web"},"32":{"name":"create_tournament::team","guard_name":"web"},"33":{"name":"update_tournament::team","guard_name":"web"},"34":{"name":"restore_tournament::team","guard_name":"web"},"35":{"name":"restore_any_tournament::team","guard_name":"web"},"36":{"name":"replicate_tournament::team","guard_name":"web"},"37":{"name":"reorder_tournament::team","guard_name":"web"},"38":{"name":"delete_tournament::team","guard_name":"web"},"39":{"name":"delete_any_tournament::team","guard_name":"web"},"40":{"name":"force_delete_tournament::team","guard_name":"web"},"41":{"name":"force_delete_any_tournament::team","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            // /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            // /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            // /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
