## About Application

Vietnam National Biodiversity Database System (Hệ Thống Cơ Sở Dữ Liệu Đa Dạng Sinh Học Thành phố Hà Nội)
Search Biodiversity Conservation Agency, Vietnam Environment Administration, Ministry of Natural Resources and Environment, Viet Nam.

- Agency [VEA](http://vea.gov.vn/)
- Deploying [CEID](http://ceid.gov.vn/).
- Developing [Skymap Global VietNam](http://skymapglobal.vn/)

## Base con

Application base on Laravel Frameword and Vue JS Framework
- [Laravel](http://laravel.com/) for backend (php versio 7.2 and laravel version 5.6)
- [VueJS](https://vuejs.org/) for front-end
- [PostgreSQL](https://www.postgresql.org/) for database managerment (version 9.6)

## Build prod

Step for build up dev environment:
1. Git clone
2. Composer (https://getcomposer.org/) install 
3. npm install
4. copy file vietnam_style.json.example to vietnam_style.json and 
change "url": "[domain]/vietnam_ocean_vt.json"
"sprite": "[domain]/vt_styles/sprite/sprite",
"glyphs": "[domain]/vt_styles/fonts/{fontstack}/{range}.pbf",
in which [domain] is server titles map (https://github.com/maptiler/tileserver-php)

