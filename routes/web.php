<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('user/confirm/{token}', 'Auth\RegisterController@verifyEmail')->name('confirm.user');
Route::get('getlang/login', 'Auth\LoginController@getLangLgoin')->name('getlang.login');

//Reset Password
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset.token');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

//Login with FB, GG
Route::get('{action}/redirect/{social}', 'SocialAuthController@redirect');
Route::get('/callback/{social}', 'SocialAuthController@callback');

//Home
Route::get('/home', 'Admin\HomeController@index')->name('admin.home');
Route::get('/admin', 'Admin\HomeController@index');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home/info', 'HomeController@infoHome')->name('home');
Route::get('setlocale/{locale}', 'HomeController@setLocale')->name('setlocale');
Route::get('check/login', 'HomeController@checkLogin');

//Dataset
Route::get('search/dataset', 'DatasetResourceController@indexView')->name('search.dataset');
Route::get('detail/dataset/{id}', 'DatasetResourceController@detail')->name('detail.dataset');
Route::get('detail/dataset/image/{id}', 'DatasetResourceController@getImage');
Route::get('datasetresources', 'DatasetResourceController@index');
Route::get('asyncdatasetresources', 'DatasetResourceController@asyncSearch');
Route::get('dataset/info-tree', 'DatasetResourceController@infoTree');
Route::get('dataset/info-percent', 'DatasetResourceController@infoPercent');

//Publisher
Route::get('toppublishers', 'PublisherController@getTopPublisher');
Route::get('publishers', 'PublisherController@index');
Route::get('asynchronous/publishers', 'PublisherController@asynchronous');
Route::get('search/publisher', 'PublisherController@indexView')->name('search.publisher');
Route::get('detail/publisher/{id}', 'PublisherController@detail')->name('detail.publisher');

//Area
Route::get('toppublishingofareas', 'AreaController@getTopPublishingOfArea');
Route::get('publishingofareas', 'AreaController@index');
Route::get('doituongkhubaoton', 'AreaController@getDoiTuongKbt');


//Protected Area
Route::get('protectedareas', 'ProtectedAreaController@index');
Route::get('topsubloc', 'ProtectedAreaController@getTopsubloc');
Route::get('topdesig', 'ProtectedAreaController@getTopDesig');
Route::get('protectedareas/topstatuses', 'ProtectedAreaController@getTopStatus');
Route::get('protectedareas/topgovtypes', 'ProtectedAreaController@getTopGovType');
Route::get('asynchronous/protectedareas', 'ProtectedAreaController@asynchronous');
Route::get('search/protectedarea', 'ProtectedAreaController@indexView')->name('search.protectedarea');
Route::get('detail/protectedarea/{id}', 'ProtectedAreaController@detail')->name('detail.protectedarea');
Route::get('detail/protectedarealistspecies/{id}', 'ProtectedAreaController@getListSpecies')->name('detail.protectedarea');
Route::get('sublocs', 'ProtectedAreaController@getSublocs');
Route::get('desigs', 'ProtectedAreaController@getDesigs');
Route::get('desigtypes', 'ProtectedAreaController@getDesigType');
Route::get('mangauths', 'ProtectedAreaController@getMangAuth');
Route::get('protectedareas/map', 'ProtectedAreaController@getDataMap');
Route::get('menudata', 'HomeController@getMenuData');

Route::get('topphanbo', 'ProtectedAreaController@getTopPhanBo');
Route::get('searchphanbo', 'ProtectedAreaController@getSearchPhanBo');

Route::get('search/standardzone', 'ProtectedAreaController@indexViewStandardZone')->name('search.standardzone');
Route::get('standardzonedata', 'ProtectedAreaController@getOTieuChuan')->name('standardzonedata');

Route::get('search/biodiversity-corridor', 'ProtectedAreaController@indexViewHanhLang')->name('search.hanhlang');
Route::get('search/hight-biodiversity', 'ProtectedAreaController@indexViewHighBiodiversity')->name('search.hightbiodiversity');
Route::get('search/important-wetlands', 'ProtectedAreaController@indexViewImportantWetland')->name('search.importantwetland');
Route::get('search/important-birdarea', 'ProtectedAreaController@indexViewImportantBirdAre')->name('search.birdarea');
Route::get('search/important-landscape', 'ProtectedAreaController@indexViewImportantLandscape')->name('search.landscape');
Route::get('search/biosphere-reserves', 'ProtectedAreaController@indexViewBiosphere')->name('search.biosphere');

//Occurrences
Route::get('search/occurrence', 'OccurrenceControler@indexView')->name('search.occurrence');
Route::get('occurrences', 'OccurrenceControler@index');
Route::get('occurrences/toppublisers', 'OccurrenceControler@getTopPublisers');
Route::get('occurrences/topdatasets', 'OccurrenceControler@getTopDatasets');
Route::get('detail/occurrence/{id}', 'OccurrenceControler@detail')->name('detail.occurrence');

//species
Route::get('species/info-percent', 'SpeciesController@infoPercent');
Route::get('species/topstatus', 'SpeciesController@getStatus');

//Search
Route::get('search', 'SearchController@index')->name('search');
Route::get('search/species', 'SearchController@indexSpecies')->name('search.species');
Route::get('getdatasearch/species', 'SearchController@getDataSpecies')->name('getdatasearch.species');
Route::get('searchall', 'SearchController@searchAll')->name('searchall');
Route::get('searchspecies', 'SearchController@searchSpecies')->name('searchspecies')->middleware('cros');;
Route::get('searchEverything', 'SearchController@searchEverything');
Route::get('getprotectedarea', 'SearchController@getProtectedArea')->name('getprotectedarea');
Route::get('getlang/species', 'SearchController@getLangSpecies')->name('getlang.species');
Route::get('getlang/dataset', 'SearchController@getLangDataset')->name('getlang.dataset');
Route::get('getregion', 'SearchController@getDataRegion')->name('getregion');
Route::get('getprovince', 'SearchController@getDataProvince')->name('getprovince');
Route::get('getdistrict', 'SearchController@getDataDistrict')->name('getdistrict');
Route::get('searchregion', 'SearchController@searchRegion')->name('searchregion');
Route::get('getlang/protectedarea', 'SearchController@getLangProtectedArea')->name('getlang.protectedarea');
Route::get('getlang/region', 'SearchController@getLangRegion')->name('getlang.region');

//Detail
Route::get('detail/species/{id}', 'DetailController@detailSpecies')->name('detail.species');
Route::get('detail/indexdataset/images/{id}', 'DetailController@getImage');

Route::get('detail/indexspecies/{id}', 'DetailController@indexDetailSpecies')->name('detail.index.species');
Route::get('detail/indexspecies/images/{id}', 'DetailController@getSpeciesImage');


Route::get('detail/indexdataset/{id}', 'DetailController@indexDetailDataset')->name('detail.index.dataset');
Route::get('detail/region/{id}', 'DetailController@detailRegion')->name('detail.region');
Route::get('detail//{id}', 'DetailController@indexDetailRegion')->name('detail.index.region');
Route::get('detail/indexprotectedarea/{id}', 'DetailController@indexDetailProtectedArea')->name('detail.index.protectedarea');
Route::get('detail/indexspecies/getTaxon/{id}', 'DetailController@getTaxon');
Route::get('detail/indexspecies/getDataset/{id}', 'DetailController@getDataset');

//Taxon
Route::get('detail/taxon/{id}', 'TaxonController@detail')->name('detail.taxon');
Route::get('search/taxon', 'TaxonController@indexView');
Route::get('taxon', 'TaxonController@index');
Route::get('taxonImage', 'TaxonController@getImage');
Route::get('taxon/topdatasets', 'TaxonController@getTopDatasets');
Route::get('taxon/datasetresources', 'TaxonController@getDatasetresources');

//Gen
Route::get('search/genetic-resources', 'GeneticController@viewSearchResources')->name('search.genetic');
Route::get('search/genetic-knowledge', 'GeneticController@viewSearchKnowledge')->name('search.knowledge');
Route::get('geneticdata', 'GeneticController@getData')->name('search.geneticdata');
Route::get('geneticdatasearch', 'GeneticController@getDataSearch')->name('search.geneticdata');
Route::get('noiluugiusearch', 'GeneticController@getNoiLuuGiu')->name('search.noiluugiu');
Route::get('tinhthanhsearch', 'GeneticController@getTinhThanh')->name('search.tinhthanh');
Route::get('quanhuyensearch', 'GeneticController@getQuanHuyen')->name('search.quanhuyen');
Route::get('detail/genetic/{id}', 'GeneticController@viewDetail')->name('search.detail');

Route::get('knowledgedata', 'GeneticController@getTriThucTruyenThong')->name('search.knowledgedata');

//He sinh thai
Route::get('search/ecosystem', 'HeSinhThaiController@viewSearchEcosystem')->name('search.ecosystem');
Route::get('ecosystemdata', 'HeSinhThaiController@getHeSinhThai')->name('search.ecosystemdata');
Route::get('search/ecosystem-service', 'HeSinhThaiController@viewSearchService')->name('search.service');
Route::get('ecosystemservicedata', 'HeSinhThaiController@getDichVuHeSinhThai')->name('search.ecosystemservicedata');
Route::get('search/data-ecosystem', 'HeSinhThaiController@viewSearchEcosystemData')->name('search.ecosystem');

//No luc bao ton
Route::get('search/international-conventions', 'SearchNoLucBaoTonController@indexDieuUocQuocTe')->name('search.conventions');
Route::get('search/international-conventions/{id}', 'SearchNoLucBaoTonController@detailDieuUocQuocTe')->name('search.conventions.detail');
Route::get('search-international-conventions', 'SearchNoLucBaoTonController@searchDieuUocQuocTe')->name('search.conventions.get');
Route::get('search-international-conventions', 'SearchNoLucBaoTonController@searchDieuUocQuocTe')->name('search.conventions');
Route::get('search/legislation-documents', 'Admin\VanBanPhapLuatController@viewSearch')->name('search.legislation');
Route::get('legislationdocuments', 'Admin\VanBanPhapLuatController@getVanBanPhapLuatSearch')->name('legislationdocuments');
Route::get('legislationdocuments/{id}', 'Admin\VanBanPhapLuatController@viewDetailSearch')->name('legislationdocuments.detail');
Route::get('search/biodiversity-permit', 'Admin\GiayPhepController@viewSearch')->name('search.permit');
Route::get('biodiversity-permit', 'Admin\GiayPhepController@getGiayPhepSearch')->name('permitdata');
Route::get('biodiversitypermit/{id}', 'Admin\GiayPhepController@viewDetailSearch')->name('permit.detail');
Route::get('search/inspect', 'Admin\ThanhTraKiemTraController@viewSearch')->name('search.inspect');
Route::get('search/alien', 'Admin\KiemSoatSinhVatNgoaiLaiController@viewSearch')->name('search.alien');
Route::get('search/cooperation', 'Admin\HopTacQuocTeController@viewSearch')->name('search.cooperation');
Route::get('search/annual-budget', 'Admin\NguonKinhPhiController@viewSearch')->name('search.budget');
Route::get('search/biodiversity-conservation-initiative', 'Admin\MoHinhSangKienController@viewSearch')->name('search.initiative');
Route::get('search/pressure-statistics', 'Admin\AplucDaDangSinhHocController@viewSearch')->name('search.pressure');

//Co sở bao tồn
Route::get('search/conservation-infrastructure', 'ConservationInfrastructureSearchController@index');
Route::get('search/conservation-infrastructure/data', 'ConservationInfrastructureSearchController@getSearchData');
Route::get('search/conservation-infrastructure/map', 'ConservationInfrastructureSearchController@getMapData');
Route::get('search/conservation-infrastructure/category', 'ConservationInfrastructureSearchController@getCategories');
Route::get('search/conservation-infrastructure/{id}', 'ConservationInfrastructureSearchController@getDetail');

Route::get('search/ecosystem', 'EcosystemSearchController@index');
Route::get('search/ecosystem/data', 'EcosystemSearchController@getSearchData');
Route::get('search/ecosystem/map', 'EcosystemSearchController@getMapData');
Route::get('search/ecosystem/category', 'EcosystemSearchController@getCategories');
Route::get('search/ecosystem/{id}', 'EcosystemSearchController@getDetail');

Route::get('search/standard-zone', 'StandardZoneSearchController@index');
Route::get('search/standard-zone/data', 'StandardZoneSearchController@getSearchData');
Route::get('search/standard-zone/map', 'StandardZoneSearchController@getMapData');
Route::get('search/standard-zone/category', 'StandardZoneSearchController@getCategories');

Route::get('search/annual-budget', 'AnnualBudgetSearchController@index');
Route::get('search/annual-budget/data', 'AnnualBudgetSearchController@getSearchData');
Route::get('search/annual-budget/category', 'AnnualBudgetSearchController@getCategories');
Route::get('search/biodiversity-conservation-initiative', 'BiodiversityConservationInitiativeSearchController@index');
Route::get('search/biodiversity-conservation-initiative/data', 'BiodiversityConservationInitiativeSearchController@getSearchData');
Route::get('search/biodiversity-conservation-initiative/category', 'BiodiversityConservationInitiativeSearchController@getCategories');
Route::get('search/control-of-alien-organisms', 'ControlOfAlienOrganismsSearchController@index');
Route::get('search/control-of-alien-organisms/data', 'ControlOfAlienOrganismsSearchController@getSearchData');
Route::get('search/control-of-alien-organisms/category', 'ControlOfAlienOrganismsSearchController@getCategories');
Route::get('search/control-of-alien-organisms/{id}', 'ControlOfAlienOrganismsSearchController@getDetail');
Route::get('search/international-cooperation', 'InternationalCooperationSearchController@index');
Route::get('search/international-cooperation/data', 'InternationalCooperationSearchController@getSearchData');
Route::get('search/international-cooperation/category', 'InternationalCooperationSearchController@getCategories');
Route::get('search/international-cooperation/{id}', 'InternationalCooperationSearchController@getDetail');
Route::get('search/legislation-documents', 'LegislationDocumentsSearchController@index');
Route::get('search/legislation-documents/data', 'LegislationDocumentsSearchController@getSearchData');
Route::get('search/legislation-documents/category', 'LegislationDocumentsSearchController@getCategories');
Route::get('search/legislation-documents/{id}', 'LegislationDocumentsSearchController@getDetail');

Route::get('search/ecosystem-service', 'EcosystemServiceSearchController@index');
Route::get('search/ecosystem-service/data', 'EcosystemServiceSearchController@getSearchData');
Route::get('search/ecosystem-service/category', 'EcosystemServiceSearchController@getCategories');

Route::get('search/alien-organisms', 'ControlOfAlienOrganismsSearchController@viewSearch');
Route::get('admin/sinhvatngoailai/chiso', 'Admin\SinhVatNgoaiLaiController@getChiSo')->name('sinhvatngoailai.chiso');
Route::get('alien-organisms', 'ControlOfAlienOrganismsSearchController@getSinhVatNgoaiLaiSearch');
Route::get('ap-luc-search', 'ControlOfAlienOrganismsSearchController@getApLucSearch');
Route::get('detail/ap-luc/{id}', 'ControlOfAlienOrganismsSearchController@viewDetailApluc');
// Route::get('ap-luc/{id}', 'ControlOfAlienOrganismsSearchController@getDetailApluc');

Route::get('search/apluc', 'Admin\AplucDaDangSinhHocController@viewSearch');


// Route::get('search/genetic-knowledge', 'GeneticKnowledgeSearchController@index');
// Route::get('search/genetic-knowledge/data', 'GeneticKnowledgeSearchController@getSearchData');
// Route::get('search/genetic-knowledge/category', 'GeneticKnowledgeSearchController@getCategories');

Route::get('search/mapv1', 'BanDoController@index');
Route::get('search/map', 'BanDoController@showMap');
Route::get('search/map-hst', 'BanDoController@showMapImage');
Route::get('dashboard', 'HomeController@dashboard');

Route::get('bieu-do-loai', 'HomeController@bieuDoLoai');
//admin
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'role:admin,national-level,provincial-level'], function () {
        //info
        Route::get('/dataset/info', 'DatasetController@getDataset');
        Route::get('/dataset/detail/taxon/info/{id}', 'DatasetController@getDatasetDetailTaxon');
        Route::get('/dataset/detail/occurrences/info/{id}', 'DatasetController@getDatasetDetailOccurrences');
        Route::get('/org-defined-format/info', 'DatasetController@getOrgDefinedFormat');
        Route::get('/dataset-type/info', 'DatasetController@getDatasetType');
        Route::get('dataset/organizations/info', 'DatasetController@getOrganization');
        Route::get('dataset/protectedarea/info', 'DatasetController@getProtectedArea');
        Route::get('dataset/status', 'DatasetController@getStatus');
        Route::get('/dataset/toporganization', 'DatasetController@getTopOrganization');
        Route::get('/dataset/datasettype', 'DatasetController@getDatasetType');
        Route::get('doituongbaoton', 'DatasetController@getDoiTuongBaoTon');


        //Species
        Route::get('ranks/info', 'SpeciesController@getRanks');
        Route::get('kingdom/info', 'SpeciesController@getKingdom');
        Route::get('phylum/info', 'SpeciesController@getPhylum');
        Route::get('class/info', 'SpeciesController@getClass');
        Route::get('order/info', 'SpeciesController@getOrder');
        Route::get('family/info', 'SpeciesController@getFamily');
        Route::get('genus/info', 'SpeciesController@getGenus');
        Route::get('species/info', 'SpeciesController@getSpecies');
        Route::get('subspecies/info', 'SpeciesController@getSubSpecies');
        Route::get('synonyms/info', 'SpeciesController@getSynonyms');
        Route::get('/species', 'SpeciesController@index')->name('species');
        Route::get('/species/add', 'SpeciesController@create')->name('add.species');
        Route::post('/species/add', 'SpeciesController@add');
        Route::get('/species/update/{rank}/{id}', 'SpeciesController@show')->name('update.species');
        Route::post('/species/update/{rank}/{id}', 'SpeciesController@update');
        Route::post('/species/upload', 'SpeciesController@uploadSpeciesRank');
        Route::delete('/species/delete/{rank}/{id}', 'SpeciesController@delete');
        Route::get('/species/dowload-template-rank', 'SpeciesController@downloadTemplateSpeciesRank');

        //Gen
        Route::get('genetic', 'GeneticController@ViewIndex')->name('genetic');
        Route::get('genetic/add', 'GeneticController@showFormAdd')->name('genetic.showadd');
        Route::get('genetic/edit/{id}', 'GeneticController@showFormEdit')->name('genetic.showedit');
        Route::get('genetic/genedit', 'GeneticController@getEditNguonGen')->name('genetic.genedit');
        Route::post('genetic/edit', 'GeneticController@editNguonGen')->name('genetic.edit');
        Route::delete('genetic/delete/{id}', 'GeneticController@xoaNguonGen')->name('genetic.delete');
        Route::get('genetic/chiso', 'GeneticController@getChiSoNguonGen')->name('genetic.chiso');

        Route::post('genetic/add', 'GeneticController@addNguonGen')->name('genetic.add');
        Route::get('genetic/getdata', 'GeneticController@getNguonGen')->name('genetic.get');
        Route::get('genetic/searchspecies', 'GeneticController@searchLoai')->name('genetic.searchspecies');
        Route::get('genetic/searchnoiluugiu', 'GeneticController@searchNoiLuuGiu')->name('genetic.searchnoiluugiu')->middleware('cros');

        Route::post('genetic/addnhom', 'GeneticController@addNhomGen')->name('genetic.addNhom');
        Route::post('genetic/editnhom', 'GeneticController@editNhomGen')->name('genetic.editnhom');
        Route::get('genetic/nhomgen', 'GeneticController@getNhomGen')->name('genetic.getnhom');
        Route::delete('genetic/xoanhom/{id}', 'GeneticController@xoaNhomGen')->name('genetic.xoaNhom');

        Route::post('genetic/addloai', 'GeneticController@addLoaiGen')->name('genetic.addLoaiGen');
        Route::post('genetic/editloai', 'GeneticController@editLoaiGen')->name('genetic.editLoaiGen');
        Route::get('genetic/loaigen', 'GeneticController@getLoaiGen')->name('genetic.getloaigen');
        Route::delete('genetic/xoaloai/{id}', 'GeneticController@xoaLoaiGen')->name('genetic.xoaloaiGen');

        Route::get('noiluugiu/diagioi', 'NoiLuuGiuNguonGenController@getTinhThanhQuanHuyen')->name('noiluugiu.diagioi');
        Route::post('noiluugiu/add', 'NoiLuuGiuNguonGenController@addNoiLuuGiu')->name('noiluugiu.add');
        Route::post('noiluugiu/edit', 'NoiLuuGiuNguonGenController@editNoiLuuGiu')->name('noiluugiu.edit');
        Route::get('noiluugiu/get', 'NoiLuuGiuNguonGenController@getNoiLuuGiu')->name('noiluugiu.get')->middleware('cros');
        Route::delete('noiluugiu/delete/{id}', 'NoiLuuGiuNguonGenController@xoaNoiLuuTru')->name('noiluugiu.delete');

        //Tri thức truyền thống
        Route::get('trithuctruyenthong', 'TriThucTruyenThongController@ViewIndex')->name('trithuctruyenthong');
        Route::get('trithuctruyenthong/searchgen', 'TriThucTruyenThongController@getNguonGenSelect')->name('trithuctruyenthong.searchgen')->middleware('cros');
        Route::post('trithuctruyenthong/add', 'TriThucTruyenThongController@addTriThucTruyenThong')->name('trithuctruyenthong.add');
        Route::get('trithuctruyenthong/get', 'TriThucTruyenThongController@getTriThucTruyenThong')->name('trithuctruyenthong.get')->middleware('cros');
        Route::post('trithuctruyenthong/update', 'TriThucTruyenThongController@updateTriThucTruyenThong')->name('trithuctruyenthong.update');
        Route::delete('trithuctruyenthong/delete/{id}', 'TriThucTruyenThongController@xoaTriThucTruyenThong')->name('trithuctruyenthong.delete');
        Route::get('trithuctruyenthong/chiso', 'TriThucTruyenThongController@getChiSo')->name('trithuctruyenthong.chiso');

        //Dataset
        Route::get('/dataset', 'DatasetController@index')->name('dataset');
        Route::get('/dataset/add', 'DatasetController@create')->name('add.dataset');
        Route::get('/dataset/detail/taxon/{id}', 'DatasetController@detailDatasetTaxon')->name('dataset.taxon');
        Route::get('/dataset/detail/occurrences/{id}', 'DatasetController@detailDatasetOccurrences')->name('dataset.occurrences');
        Route::post('/dataset/add', 'DatasetController@add');
        Route::get('/dataset/update/{id}', 'DatasetController@show')->name('update.dataset');
        Route::post('/dataset/update/{id}', 'DatasetController@update');
        Route::delete('/dataset/delete/{id}', 'DatasetController@delete');
        Route::post('/dataset/taxon/upload/{id}', 'DatasetController@uploadTaxon');
        Route::post('/dataset/occurrences/upload/{id}', 'DatasetController@uploadOccurrences');
        Route::get('/dataset/exportExcelDataSet/{id}', 'DatasetController@export');
        //Protected Area
        Route::get('protectedareas/info', 'ProtectedAreaController@getProtectedArea')->middleware('cros');
        Route::get('protectedareas', 'ProtectedAreaController@index')->name('protectedareas')->middleware('cros');
        Route::get('/protectedareas/add', 'ProtectedAreaController@create')->name('add.protectedareas');
        Route::post('/protectedareas/add', 'ProtectedAreaController@add');
        Route::get('/protectedareas/update/{id}', 'ProtectedAreaController@show')->name('update.protectedareas');
        Route::post('/protectedareas/update/{id}', 'ProtectedAreaController@update');
        Route::delete('/protectedareas/delete/{id}', 'ProtectedAreaController@delete');
        Route::post('/protectedareas/uploadImage/{id}', 'ProtectedAreaController@uploadImage');

        //Organization
        Route::get('organizations', 'OrganizationController@index')->name('organizations');
        Route::get('organizations/info', 'OrganizationController@getOrganization');
        Route::get('organizations/add', 'OrganizationController@create')->name('organizations.create');
        Route::post('organizations/add', 'OrganizationController@add');
        Route::get('organizations/update/{id}', 'OrganizationController@show')->name('organizations.update');
        Route::post('organizations/update/{id}', 'OrganizationController@update');
        Route::delete('organizations/delete/{id}', 'OrganizationController@delete');

        //Chi thi
        Route::get('chithi', 'DanhMucController@chiThiView')->name('chithi');
        Route::post('chithi/addnhom', 'DanhMucController@addNhomChiThi')->name('add.nhomchithi');
        Route::post('chithi/editnhom', 'DanhMucController@editNhomChiThi')->name('edit.nhomchithi');
        Route::get('chithi/getnhom', 'DanhMucController@getNhomChiThi')->name('nhomchithi');
        Route::delete('chithi/xoanhom/{id}', 'DanhMucController@xoaNhomChiThi')->name('add.xoanhom');
        Route::delete('chithi/xoa/{id}', 'DanhMucController@xoaChiThi')->name('add.xoa');

        Route::post('chithi/add', 'DanhMucController@addChiThi')->name('add.chithi');
        Route::post('chithi/edit', 'DanhMucController@editChiThi')->name('edit.chithi');
        Route::get('chithi/getchithi', 'DanhMucController@getChiThi')->name('get.chithi')->middleware('cros');

        Route::get('thongso/{id}', 'DanhMucController@thongSoView')->name('thongso.show');
        Route::get('thongso', 'DanhMucController@getThongSo')->name('thongso');
        Route::put('thongso', 'DanhMucController@editThongSo')->name('thongso.update');
        Route::post('thongso', 'DanhMucController@addThongSo')->name('thongso.create');
        Route::delete('thongso/delete/{id}', 'DanhMucController@xoaThongSo')->name('thongso.delete');

        Route::get('bangtin/get', 'DanhMucController@getBangTin')->name('bangtin.get');
        Route::get('bangtin', 'DanhMucController@viewBangTin')->name('bangtin');
        Route::post('bangtin/add', 'DanhMucController@addBangTin')->name('bangtin.add');
        Route::post('bangtin/edit', 'DanhMucController@editBangTin')->name('bangtin.edit');

        Route::get('apluc/chiso', 'DanhMucController@getChiSoApluc')->name('bangtin.chiso');
        Route::get('apluc', 'DanhMucController@viewApLuc')->name('apluc');
        Route::get('apluc/get', 'DanhMucController@getBaoCaoApLuc')->name('apluc.get');
        Route::post('apluc/add', 'DanhMucController@addBaoCaoApLuc')->name('apluc.add');
        Route::post('apluc/edit', 'DanhMucController@editBaoCaoApLuc')->name('apluc.edit');
        Route::delete('apluc/delete/{id}', 'DanhMucController@xoaBaoCaoApluc')->name('apluc.delete');

        //Quan trac
        Route::get('quantrac', 'QuanTracController@viewIndex')->name('quantrac');
        Route::get('quantrac/loaihinh', 'QuanTracController@getLoaiHinh')->name('quantrac.getloaihinh');
        Route::post('quantrac/loaihinh', 'QuanTracController@addLoaiHinh')->name('quantrac.addloaihinh');
        Route::put('quantrac/loaihinh', 'QuanTracController@editLoaiHinh')->name('quantrac.editloaihinh');
        Route::delete('quantrac/loaihinh/{id}', 'QuanTracController@xoaLoaiHinh')->name('quantrac.xoaloaihinh');

        Route::get('quantrac/thongso', 'QuanTracController@getThongSo')->name('quantrac.getthongso');
        Route::post('quantrac/thongso', 'QuanTracController@addThongSo')->name('quantrac.addthongso');
        Route::put('quantrac/thongso', 'QuanTracController@editThongSo')->name('quantrac.editthongso');
        Route::get('quantrac/khubaoton', 'QuanTracController@getKhuBaoTon')->name('quantrac.getkhubaoton');
        Route::delete('quantrac/thongso/{id}', 'QuanTracController@xoaThongSo')->name('quantrac.xoathongso');

        Route::post('quantrac/diemquantrac', 'QuanTracController@addDiemQuanTrac')->name('quantrac.adddiemquantrac');
        Route::put('quantrac/diemquantrac', 'QuanTracController@editDiemQuanTrac')->name('quantrac.editdiemquantrac');
        Route::get('quantrac/diemquantrac', 'QuanTracController@getDiemQuanTrac')->name('quantrac.diemquantrac');
        Route::delete('quantrac/diemquantrac/delete/{id}', 'QuanTracController@xoaDiemQuanTrac')->name('quantrac.xoadiemquantrac');

        //Resource lop thong tin
        Route::post('uploadfiles', 'DieuUocQuocTeController@uploadFile')->name('uploadfiles');
        //Dieu uoc quoc te
        Route::get('dieuuocquocte', 'DieuUocQuocTeController@viewIndex')->name('dieuuocquocte');
        Route::get('dieuuocquocte/list', 'DieuUocQuocTeController@getDieuUocQuocTe')->name('dieuuocquocte.list');
        Route::put('dieuuocquocte/edit', 'DieuUocQuocTeController@editDieuUoc')->name('dieuuocquocte.edit');
        Route::post('dieuuocquocte/add', 'DieuUocQuocTeController@addDieuUoc')->name('dieuuocquocte.add');
        Route::delete('dieuuocquocte/delete/{id}', 'DieuUocQuocTeController@xoaDieuUoc')->name('dieuuocquocte.delete');

        //van ban phap luat
        Route::get('vanbanphapluat', 'VanBanPhapLuatController@viewIndex')->name('vanbanphapluat');
        Route::get('vanbanphapluat/list', 'VanBanPhapLuatController@getVanBanPhapLuat')->name('vanbanphapluat.list');
        Route::put('vanbanphapluat/edit', 'VanBanPhapLuatController@editVanBanPhapluat')->name('vanbanphapluat.edit');
        Route::post('vanbanphapluat/add', 'VanBanPhapLuatController@addVanBanPhapLuat')->name('vanbanphapluat.add');
        Route::get('vanbanphapluat/getdanhmuc', 'VanBanPhapLuatController@getDanhMuc')->name('vanbanphapluat.getdanhmuc');
        Route::get('vanbanphapluat/chiso', 'VanBanPhapLuatController@getChiSo')->name('vanbanphapluat.getchiso');
        Route::delete('vanbanphapluat/delete/{id}', 'VanBanPhapLuatController@xoaVanBanPhapLuat')->name('vanbanphapluat.delete');

        //Giay phep da dang sinh hoc
        Route::get('giayphep', 'GiayPhepController@viewIndex')->name('giayphep');
        Route::get('giayphep/list', 'GiayPhepController@getGiayPhep')->name('giayphep.list');
        Route::put('giayphep/edit', 'GiayPhepController@editGiayPhep')->name('giayphep.edit');
        Route::put('giayphep/editv2', 'GiayPhepController@editGiayPhepv2')->name('giayphep.editv2');
        Route::get('giayphep/add', 'GiayPhepController@showFormAdd')->name('giayphep.showadd');
        Route::get('giayphep/edit/{id}', 'GiayPhepController@showFormEdit')->name('giayphep.showedit');
        Route::post('giayphep/add', 'GiayPhepController@addGiayPhep')->name('giayphep.add');
        Route::post('giayphep/addv2', 'GiayPhepController@addGiayPhepv2')->name('giayphep.addv2');
        Route::delete('giayphep/delete/{id}', 'GiayPhepController@xoaGiayPhep')->name('giayphep.delete');

        //Hop tac quoc te
        Route::get('hoptacquocte', 'HopTacQuocTeController@viewIndex')->name('hoptacquocte');
        Route::get('hoptacquocte/list', 'HopTacQuocTeController@getHopTacQuocTe')->name('hoptacquocte.list')->middleware('cros');
        Route::put('hoptacquocte/edit', 'HopTacQuocTeController@editHopTacQuocTe')->name('hoptacquocte.edit');
        Route::post('hoptacquocte/add', 'HopTacQuocTeController@addHopTacQuocTe')->name('hoptacquocte.add');
        Route::get('hoptacquocte/showadd', 'HopTacQuocTeController@showFormAdd')->name('hoptacquocte.showadd');
        Route::get('hoptacquocte/edit/{id}', 'HopTacQuocTeController@showFormEdit')->name('hoptacquocte.show');
        Route::delete('hoptacquocte/delete/{id}', 'HopTacQuocTeController@xoaHopTacQuocTe')->name('hoptacquocte.delete');

        //Mo hinh sang kien bao ton da dang sinh hoc
        Route::get('mohinhsangkien', 'MoHinhSangKienController@viewIndex')->name('mohinhsangkien');
        Route::get('mohinhsangkien/list', 'MoHinhSangKienController@getMoHinhSangKien')->name('mohinhsangkien.list')->middleware('cros');
        Route::put('mohinhsangkien/edit', 'MoHinhSangKienController@editMoHinhSangKien')->name('mohinhsangkien.edit');
        Route::post('mohinhsangkien/add', 'MoHinhSangKienController@addMoHinhSangKien')->name('mohinhsangkien.add');
        Route::get('mohinhsangkien/chiso', 'MoHinhSangKienController@getChiSo')->name('mohinhsangkien.chiso');
        Route::delete('mohinhsangkien/delete/{id}', 'MoHinhSangKienController@xoaMoHinh')->name('mohinhsangkien.delete');

        //Kiem soat sinh vat ngoai lai xam hai
        Route::get('chuongtrinhdetai', 'KiemSoatSinhVatNgoaiLaiController@viewIndex')->name('chuongtrinhdetai');
        Route::get('chuongtrinhdetai/list', 'KiemSoatSinhVatNgoaiLaiController@getDeTai')->name('chuongtrinhdetai.list')->middleware('cros');
        Route::put('chuongtrinhdetai/edit', 'KiemSoatSinhVatNgoaiLaiController@editDeTai')->name('chuongtrinhdetai.edit');
        Route::post('chuongtrinhdetai/add', 'KiemSoatSinhVatNgoaiLaiController@addDeTai')->name('chuongtrinhdetai.add');
        Route::delete('chuongtrinhdetai/delete/{id}', 'KiemSoatSinhVatNgoaiLaiController@xoaDeTai')->name('chuongtrinhdetai.delete');

        //Kiem soat sinh vat ngoai lai xam hai
        Route::get('thanhtrakiemtra', 'ThanhTraKiemTraController@viewIndex')->name('thanhtrakiemtra');
        Route::get('thanhtrakiemtra/list', 'ThanhTraKiemTraController@getDeTai')->name('thanhtrakiemtra.list');
        Route::put('thanhtrakiemtra/edit', 'ThanhTraKiemTraController@editDeTai')->name('thanhtrakiemtra.edit');
        Route::post('thanhtrakiemtra/add', 'ThanhTraKiemTraController@addDeTai')->name('thanhtrakiemtra.add');

        //Kinh phi hang nam
        Route::get('kinhphi', 'NguonKinhPhiController@viewIndex')->name('kinhphi');
        Route::get('kinhphi/get', 'NguonKinhPhiController@getKinhPhi')->name('kinhphi.get');
        Route::post('kinhphi/add', 'NguonKinhPhiController@addKinhPhi')->name('kinhphi.add');
        Route::post('kinhphi/edit', 'NguonKinhPhiController@updateKinhPhi')->name('kinhphi.edit');
        Route::get('kinhphi/chiso', 'NguonKinhPhiController@getChiSo')->name('kinhphi.chiso');
        Route::delete('kinhphi/delete/{id}', 'NguonKinhPhiController@xoaKinhPhi')->name('kinhphi.delete');

        //He sinh thai
        Route::get('hesinhthai', 'HeSinhThaiController@viewIndex')->name('hesinhthai');
        Route::get('hesinhthai/get', 'HeSinhThaiController@getHeSinhThai')->name('hesinhthai.get');
        Route::get('hesinhthai/add', 'HeSinhThaiController@showFormAdd')->name('hesinhthai.showAdd');
        Route::get('hesinhthai/edit/{id}', 'HeSinhThaiController@showFormEdit')->name('hesinhthai.showEdit');
        Route::post('hesinhthai/add', 'HeSinhThaiController@add')->name('hesinhthai.add');
        Route::post('hesinhthai/edit', 'HeSinhThaiController@edit')->name('hesinhthai.edit');
        Route::delete('hesinhthai/delete/{id}', 'HeSinhThaiController@delete')->name('hesinhthai.delete');
        Route::get('hesinhthai/chiso', 'HeSinhThaiController@getChiSo')->name('hesinhthai.chiso');

        Route::get('dichvuhesinhthai', 'DichVuHeSinhThaiController@viewIndex')->name('dichvuhesinhthai');
        Route::get('dichvuhesinhthai/get', 'DichVuHeSinhThaiController@getDichVu')->name('dichvuhesinhthai.get')->middleware('cros');
        Route::post('dichvuhesinhthai/add', 'DichVuHeSinhThaiController@add')->name('dichvuhesinhthai.add');
        Route::post('dichvuhesinhthai/edit', 'DichVuHeSinhThaiController@update')->name('dichvuhesinhthai.edit');
        Route::get('dichvuhesinhthai/chiso', 'DichVuHeSinhThaiController@getChiSo')->name('dichvuhesinhthai.chiso');
        Route::delete('dichvuhesinhthai/delete/{id}', 'DichVuHeSinhThaiController@delete')->name('dichvuhesinhthai.delete');

        //O tieu chuan
        Route::get('otieuchuan', 'OTieuChuanController@viewIndex')->name('otieuchuan');
        Route::post('otieuchuan/add', 'OTieuChuanController@add')->name('otieuchuan.add');
        Route::post('otieuchuan/edit', 'OTieuChuanController@edit')->name('otieuchuan.edit');
        Route::delete('otieuchuan/delete/{id}', 'OTieuChuanController@delete')->name('otieuchuan.delete');
        Route::get('otieuchuan/get', 'OTieuChuanController@getOTieuChuan')->name('otieuchuan.get')->middleware('cros');

        //Ket qua quan trac
        Route::post('ketquaquantrac/add', 'QuanTracController@addKetQua')->name('ketquaquantrac.add');
        Route::post('ketquaquantrac/edit', 'QuanTracController@editKetQua')->name('ketquaquantrac.edit');
        Route::delete('ketquaquantrac/delete/{id}', 'QuanTracController@deleteKetQua')->name('ketquaquantrac.delete');
        Route::get('ketquaquantrac/get', 'QuanTracController@getKetQua')->name('ketquaquantrac.get');
        Route::get('ketquaquantrac/danhmuc', 'QuanTracController@getDanhMucKetQuaQuanTrac')->name('ketquaquantrac.danhmuc');

        //Co so bao ton
        Route::get('cosobaoton', 'CoSoBaoTonController@viewIndex')->name('cosobaoton');
        Route::get('cosobaoton/get', 'CoSoBaoTonController@getCoSoBaoTon')->name('cosobaoton.get');
        Route::get('cosobaoton/add', 'CoSoBaoTonController@showFormAdd')->name('cosobaoton.showAdd');
        Route::get('cosobaoton/edit/{id}', 'CoSoBaoTonController@showFormEdit')->name('cosobaoton.showEdit');
        Route::post('cosobaoton/add', 'CoSoBaoTonController@add')->name('cosobaoton.add');
        Route::post('cosobaoton/edit', 'CoSoBaoTonController@edit')->name('cosobaoton.edit');
        Route::delete('cosobaoton/delete/{id}', 'CoSoBaoTonController@delete')->name('cosobaoton.delete');
        Route::get('cosobaoton/chiso', 'CoSoBaoTonController@getChiSo')->name('cosobaoton.chiso');

        //Hanh lang ddsh
        Route::get('hanhlangdadangsinhhoc', 'HanhLangSinhHocController@viewIndex')->name('hanhlangdadangsinhhoc');
        Route::get('hanhlangdadangsinhhoc/get', 'HanhLangSinhHocController@getHanhLang')->name('hanhlangdadangsinhhoc.get');
        Route::get('hanhlangdadangsinhhoc/add', 'HanhLangSinhHocController@showFormAdd')->name('hanhlangdadangsinhhoc.showAdd');
        Route::get('hanhlangdadangsinhhoc/edit/{id}', 'HanhLangSinhHocController@showFormEdit')->name('hanhlangdadangsinhhoc.showEdit');
        Route::post('hanhlangdadangsinhhoc/add', 'HanhLangSinhHocController@add')->name('hanhlangdadangsinhhoc.add');
        Route::post('hanhlangdadangsinhhoc/edit', 'HanhLangSinhHocController@update')->name('hanhlangdadangsinhhoc.edit');
        Route::delete('hanhlangdadangsinhhoc/delete/{id}', 'HanhLangSinhHocController@delete')->name('hanhlangdadangsinhhoc.delete');
        Route::get('hanhlangdadangsinhhoc/chiso', 'HanhLangSinhHocController@getChiSo')->name('hanhlangdadangsinhhoc.chiso');

        //Da dang sinh hoc cao
        Route::get('khuvucdadangsinhhoccao', 'KhuDaDangSinhHocCaoController@viewIndex')->name('khuvucdadangsinhhoccao');
        Route::get('khuvucdadangsinhhoccao/get', 'KhuDaDangSinhHocCaoController@getKhuVuc')->name('khuvucdadangsinhhoccao.get');
        Route::get('khuvucdadangsinhhoccao/add', 'KhuDaDangSinhHocCaoController@showFormAdd')->name('khuvucdadangsinhhoccao.showAdd');
        Route::get('khuvucdadangsinhhoccao/edit/{id}', 'KhuDaDangSinhHocCaoController@showFormEdit')->name('khuvucdadangsinhhoccao.showEdit');
        Route::post('khuvucdadangsinhhoccao/add', 'KhuDaDangSinhHocCaoController@add')->name('khuvucdadangsinhhoccao.add');
        Route::post('khuvucdadangsinhhoccao/edit', 'KhuDaDangSinhHocCaoController@update')->name('khuvucdadangsinhhoccao.edit');
        Route::delete('khuvucdadangsinhhoccao/delete/{id}', 'KhuDaDangSinhHocCaoController@delete')->name('khuvucdadangsinhhoccao.delete');
        Route::get('khuvucdadangsinhhoccao/chiso', 'KhuDaDangSinhHocCaoController@getChiSo')->name('khuvucdadangsinhhoccao.chiso');

        //Vung dat ngap nuoc quan trong
        Route::get('datngapnuocquantrong', 'DatNgapNuocController@viewIndex')->name('datngapnuocquantrong');
        Route::get('datngapnuocquantrong/get', 'DatNgapNuocController@getKhuVuc')->name('datngapnuocquantrong.get');
        Route::get('datngapnuocquantrong/add', 'DatNgapNuocController@showFormAdd')->name('datngapnuocquantrong.showAdd');
        Route::get('datngapnuocquantrong/edit/{id}', 'DatNgapNuocController@showFormEdit')->name('datngapnuocquantrong.showEdit');
        Route::post('datngapnuocquantrong/add', 'DatNgapNuocController@add')->name('datngapnuocquantrong.add');
        Route::post('datngapnuocquantrong/edit', 'DatNgapNuocController@update')->name('datngapnuocquantrong.edit');
        Route::delete('datngapnuocquantrong/delete/{id}', 'DatNgapNuocController@delete')->name('datngapnuocquantrong.delete');
        Route::get('datngapnuocquantrong/chiso', 'DatNgapNuocController@getChiSo')->name('datngapnuocquantrong.chiso');

        //Vung chim quan trong
        Route::get('vungchimquantrong', 'VungChimQuanTrongController@viewIndex')->name('vungchimquantrong');
        Route::get('vungchimquantrong/get', 'VungChimQuanTrongController@getVungChim')->name('vungchimquantrong.get');
        Route::get('vungchimquantrong/add', 'VungChimQuanTrongController@showFormAdd')->name('vungchimquantrong.showAdd');
        Route::get('vungchimquantrong/edit/{id}', 'VungChimQuanTrongController@showFormEdit')->name('vungchimquantrong.showEdit');
        Route::post('vungchimquantrong/add', 'VungChimQuanTrongController@add')->name('vungchimquantrong.add');
        Route::post('vungchimquantrong/edit', 'VungChimQuanTrongController@update')->name('vungchimquantrong.edit');
        Route::delete('vungchimquantrong/delete/{id}', 'VungChimQuanTrongController@delete')->name('vungchimquantrong.delete');
        Route::get('vungchimquantrong/chiso', 'VungChimQuanTrongController@getChiSo')->name('vungchimquantrong.chiso');

        //Khu du tru sinh quyen
        Route::get('khudutrusinhquyen', 'KhuDuTruSinhQuyenController@viewIndex')->name('khudutrusinhquyen');
        Route::get('khudutrusinhquyen/get', 'KhuDuTruSinhQuyenController@getKhuVuc')->name('khudutrusinhquyen.get');
        Route::get('khudutrusinhquyen/add', 'KhuDuTruSinhQuyenController@showFormAdd')->name('khudutrusinhquyen.showAdd');
        Route::get('khudutrusinhquyen/edit/{id}', 'KhuDuTruSinhQuyenController@showFormEdit')->name('khudutrusinhquyen.showEdit');
        Route::post('khudutrusinhquyen/add', 'KhuDuTruSinhQuyenController@add')->name('khudutrusinhquyen.add');
        Route::post('khudutrusinhquyen/edit', 'KhuDuTruSinhQuyenController@update')->name('khudutrusinhquyen.edit');
        Route::delete('khudutrusinhquyen/delete/{id}', 'KhuDuTruSinhQuyenController@delete')->name('khudutrusinhquyen.delete');
        Route::get('khudutrusinhquyen/chiso', 'KhuDuTruSinhQuyenController@getChiSo')->name('khudutrusinhquyen.chiso');

        //Khu cảnh quan sinh thái quan trọng
        Route::get('khucanhquansinhthai', 'KhuCanhQuanSinhThaiController@viewIndex')->name('khucanhquansinhthai');
        Route::get('khucanhquansinhthai/get', 'KhuCanhQuanSinhThaiController@getKhuVuc')->name('khucanhquansinhthai.get');
        Route::get('khucanhquansinhthai/add', 'KhuCanhQuanSinhThaiController@showFormAdd')->name('khucanhquansinhthai.showAdd');
        Route::get('khucanhquansinhthai/edit/{id}', 'KhuCanhQuanSinhThaiController@showFormEdit')->name('khucanhquansinhthai.showEdit');
        Route::post('khucanhquansinhthai/add', 'KhuCanhQuanSinhThaiController@add')->name('khucanhquansinhthai.add');
        Route::post('khucanhquansinhthai/edit', 'KhuCanhQuanSinhThaiController@update')->name('khucanhquansinhthai.edit');
        Route::delete('khucanhquansinhthai/delete/{id}', 'KhuCanhQuanSinhThaiController@delete')->name('khucanhquansinhthai.delete');
        Route::get('khucanhquansinhthai/chiso', 'KhuCanhQuanSinhThaiController@getChiSo')->name('khudutrusinhquyen.chiso');

        //Sinh vật ngoại lai
        Route::get('sinhvatngoailai', 'SinhVatNgoaiLaiController@viewIndex')->name('sinhvatngoailai');
        Route::get('sinhvatngoailai/get', 'SinhVatNgoaiLaiController@getSinhVat')->name('sinhvatngoailai.get')->middleware('cros');
        Route::get('sinhvatngoailai/add', 'SinhVatNgoaiLaiController@showFormAdd')->name('sinhvatngoailai.showAdd');
        Route::get('sinhvatngoailai/edit/{id}', 'SinhVatNgoaiLaiController@showFormEdit')->name('sinhvatngoailai.showEdit');
        Route::post('sinhvatngoailai/add', 'SinhVatNgoaiLaiController@add')->name('sinhvatngoailai.add');
        Route::post('sinhvatngoailai/edit', 'SinhVatNgoaiLaiController@update')->name('sinhvatngoailai.edit');
        Route::delete('sinhvatngoailai/delete/{id}', 'SinhVatNgoaiLaiController@delete')->name('sinhvatngoailai.delete');

        //Public info
        Route::get('/tools', 'SystemController@indexTools')->name('tools');
        Route::get('/public', 'SystemController@public');
        Route::delete('medias/{id}', 'MediaController@destroy');

        // Doan thanh tra
        Route::get('doanthanhtra', 'DoanThanhTraController@viewIndex')->name('doanthanhtra');
        Route::get('doanthanhtra/get', 'DoanThanhTraController@getDoanThanhTra')->name('doanthanhtra.get');
        Route::post('doanthanhtra/add', 'DoanThanhTraController@addDoanThanhTra')->name('doanthanhtra.add');
        Route::put('doanthanhtra/edit', 'DoanThanhTraController@updateDoanThanhTra')->name('doanthanhtra.edit');
        Route::get('doanthanhtra/chiso', 'DoanThanhTraController@getChiSo')->name('doanthanhtra.chiso');
        Route::delete('doanthanhtra/delete/{id}', 'DoanThanhTraController@xoaDoanThanhTra')->name('doanthanhtra.delete');

        //Taxon - Loai
        Route::get('taxon', 'TaxonController@viewIndex')->name('taxon');
        Route::get('taxon/get', 'TaxonController@getTaxon')->name('taxon.get');
        Route::get('taxon/add', 'TaxonController@showFormAdd')->name('taxon.showAdd');
        Route::get('taxon/{id}', 'TaxonController@showFormEdit')->name('taxon.showEdit');
        Route::post('taxon', 'TaxonController@store')->name('taxon.add');
        Route::post('taxon/{id}', 'TaxonController@update')->name('taxon.edit');
        Route::delete('taxon/{id}', 'TaxonController@destroy')->name('taxon.delete');
        Route::get('taxon/chiso', 'TaxonController@getChiSo')->name('taxon.chiso');
        Route::get('ketquathanhtracoso/get', 'KetQuaThanhTraCoSoController@getKetQuaThanhTraCoSo')->name('ketquathanhtracoso.get');
        Route::resource('ketquathanhtracoso', 'KetQuaThanhTraCoSoController');
        Route::get('ketquathanhtratinh/get', 'KetQuaThanhTraTinhController@getKetQuaThanhTraTinh')->name('ketquathanhtratinh.get');
        Route::resource('ketquathanhtratinh', 'KetQuaThanhTraTinhController');

        Route::get('diagioi', 'DanhMucController@viewDiaGioi')->name('diagioi');
        Route::get('quanhuyenhanoi', 'DanhMucController@getQuanHuyen')->name('quanhuyenhanoi');

        Route::get('/user-log', 'SystemController@indexUserLog');
    });
});

Route::get('gbifimages', 'SpeciesController@getGbifMedia');

Route::get('hesinhthai/get', 'HeSinhThaiController@getHeSinhThai');
Route::get('genetic/getdata', 'Admin\GeneticController@getNguonGen')->middleware('cros');;
Route::get('apluc/get', 'Admin\DanhMucController@getBaoCaoApLuc');

//download
Route::get('/download/template/taxon', 'Admin\DatasetController@downloadTemplateTaxon');
Route::get('/download/template/occurrences', 'Admin\DatasetController@downloadTemplateOccurrences');

//Profile
Route::get('profile', 'Admin\ProfileController@show')->name('profile');
Route::post('profile', 'Admin\ProfileController@update')->name('profile.update');

Route::get('user/profile', 'Admin\UserController@profile')->name('user.profile');
Route::post('user/profile/update/{id}', 'Admin\UserController@updateProfile')->name('user.profile.update');

//Tool
Route::get('tool/species', 'SearchController@getSpeciesGbif');
Route::get('tool/occurrence', 'SearchController@getOccurrenceGbif');
Route::get('tool/summary', 'SearchController@showSummary');
Route::get('tool/category', 'SearchController@showCategory');

//Api
Route::get('nbds/category', 'SearchController@getNbdsApi');

Route::post('uploadimage', 'SearchController@uploadImage');

// options
Route::get('option/{name}', 'HomeController@getOptionByName');

Route::get('importclasses', 'Admin\ThanhTraKiemTraController@importClass');
Route::get('importorder', 'Admin\ThanhTraKiemTraController@importOrder');
Route::get('importfamily', 'Admin\ThanhTraKiemTraController@importFamily');
Route::get('importgenus', 'Admin\ThanhTraKiemTraController@importGenus');
Route::get('importspecies', 'Admin\ThanhTraKiemTraController@importSpecies');

includeRouteFiles(__DIR__ . '/web/');
