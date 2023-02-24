<?php

use App\Lookup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateLoaiHinhToChucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lookup::where('group', 'loaiHinhToChucCoSoBaoTon')->delete();
        $loaiHinh = [
            [
                'name' => 'Công Ty cổ phần',
                'code' => 'cong_ty_cp',
                'group' => 'loaiHinhToChucCoSoBaoTon',
            ],
            [
                'name' => 'Công ty nhà nước',
                'code' => 'cong_ty_nn',
                'group' => 'loaiHinhToChucCoSoBaoTon',
            ],
            [
                'name' => 'Công ty tư nhân',
                'code' => 'cong_ty_tn',
                'group' => 'loaiHinhToChucCoSoBaoTon',
            ],
            [
                'name' => 'Trung tâm thuộc quản lý nhà nước',
                'code' => 'tn_nn',
                'group' => 'loaiHinhToChucCoSoBaoTon',
            ],
            [
                'name' => 'Cá nhân hộ gia đình',
                'code' => 'ca_nhan_ho_gia_dinh',
                'group' => 'loaiHinhToChucCoSoBaoTon',
            ],
            [
                'name' => 'Loại hình khác',
                'code' => 'loai_hinh_khac',
                'group' => 'loaiHinhToChucCoSoBaoTon',
            ],
        ];

        DB::beginTransaction();
        foreach ($loaiHinh as $item) {
            $checkAvaliable = Lookup::where('name', $item['name'])->where('code', $item['code'])->where('group', $item['group'])->first();
            if (!$checkAvaliable) {
                Lookup::create($item);
            }
        }
        DB::commit();
        echo 'Seeder Done';
        return;
    }
}
