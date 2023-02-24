<?php

use App\Classes;
use App\Family;
use App\Genus;
use App\KingDom;
use App\Order;
use App\PhyLum;
use Illuminate\Database\Seeder;

class TrimdataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kingdoms = KingDom::get();
        $phylums = PhyLum::get();
        $classes = Classes::get();
        $orders = Order::get();
        $family = Family::get();
        $genus = Genus::get();
        foreach ($kingdoms as $item) {
            $row =  KingDom::where('id', $item['id'])->first();
            $name = $row->name;
            $name_vi = $row->name_vietnamese;
            $name = preg_replace('/[\s]+/mu', ' ',  $name);
            $name_vi = preg_replace('/[\s]+/mu', ' ',  $name_vi);
            $row->update([
                'name' => trim($name),
                'name_vietnamese' => trim($name_vi)
            ]);
        }
        echo 'Done 1/7';
        foreach ($phylums as $item) {
            $row =  PhyLum::where('id', $item['id'])->first();
            $name = $row->name;
            $name_vi = $row->name_vietnamese;
            $name = preg_replace('/[\s]+/mu', ' ',  $name);
            $name_vi = preg_replace('/[\s]+/mu', ' ',  $name_vi);
            $row->update([
                'name' => trim($name),
                'name_vietnamese' => trim($name_vi)
            ]);
        }
        echo 'Done 2/7';
        foreach ($classes as $item) {
            $row =  Classes::where('id', $item['id'])->first();
            $name = $row->name;
            $name_vi = $row->name_vietnamese;
            $name = preg_replace('/[\s]+/mu', ' ',  $name);
            $name_vi = preg_replace('/[\s]+/mu', ' ',  $name_vi);
            $row->update([
                'name' => trim($name),
                'name_vietnamese' => trim($name_vi)
            ]);
        }
        echo 'Done 3/7';
        foreach ($classes as $item) {
            $row =  Classes::where('id', $item['id'])->first();
            $name = $row->name;
            $name_vi = $row->name_vietnamese;
            $name = preg_replace('/[\s]+/mu', ' ',  $name);
            $name_vi = preg_replace('/[\s]+/mu', ' ',  $name_vi);
            $row->update([
                'name' => trim($name),
                'name_vietnamese' => trim($name_vi)
            ]);
        }
        echo 'Done 4/7';
        foreach ($orders as $item) {
            $row =  Order::where('id', $item['id'])->first();
            $name = $row->name;
            $name_vi = $row->name_vietnamese;
            $name = preg_replace('/[\s]+/mu', ' ',  $name);
            $name_vi = preg_replace('/[\s]+/mu', ' ',  $name_vi);
            $row->update([
                'name' => trim($name),
                'name_vietnamese' => trim($name_vi)
            ]);
        }
        echo 'Done 5/7';
        foreach ($family as $item) {
            $row =  Family::where('id', $item['id'])->first();
            $name = $row->name;
            $name_vi = $row->name_vietnamese;
            $name = preg_replace('/[\s]+/mu', ' ',  $name);
            $name_vi = preg_replace('/[\s]+/mu', ' ',  $name_vi);
            $row->update([
                'name' => trim($name),
                'name_vietnamese' => trim($name_vi)
            ]);
        }
        echo 'Done 6/7';
        foreach ($genus as $item) {
            $row =  Genus::where('id', $item['id'])->first();
            $name = $row->name;
            $name_vi = $row->name_vietnamese;
            $name = preg_replace('/[\s]+/mu', ' ',  $name);
            $name_vi = preg_replace('/[\s]+/mu', ' ',  $name_vi);
            $row->update([
                'name' => trim($name),
                'name_vietnamese' => trim($name_vi)
            ]);
        }
        echo 'Done All';
    }
}
