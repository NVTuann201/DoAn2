<template>
  <div class="white-box">
    <!-- .left-right-aside-column-->
    <div class="card-body">
      <components-loading-page v-if="loading"></components-loading-page>
      <components-notifications :typeInput="typeNotification" :textInput="textNotification">
      </components-notifications>
      <!-- /.left-aside-column-->
      <button type="button" class="btn btn-default btn-rounded m-t-10 float-right" @click="goback()">
        <i class="fa fa-arrow-left"></i>
        Trở về
      </button>
      <button type="button" class="btn btn-default btn-rounded m-t-10 float-right" @click="downloadTemplate()">
        Tải file mẫu
      </button>
      <button class="btn btn-info btn-file btn-rounded m-t-10">
        <i class="fa fa-paperclip"></i> Chọn file excel
        <input type="file" id="file" ref="file" v-on:change="uploadFile()"
          accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
      </button>
      <a v-if="this.arrayLoi.length != 0" type="button" class="btn btn-default btn-rounded m-t-10 float-right"
        id="import_loi">
        <download-excel :data="this.arrayLoi" name="import_loi.xls" :fields="field_excel">
          Tải file báo cáo lỗi
        </download-excel>
      </a>
      <div class="right-page-header">
        <div class="scrollable">
          <div class="table-responsive">
            <table class="
                table
                m-t-30
                table-hover
                contact-list
                footable footable-1 footable-paging footable-paging-center
                breakpoint-lg
              ">
              <thead>
                <tr class="footable-header" style="position: sticky">
                  <th class="text-center">{{ $t("admin.label.no") }}</th>
                  <th style="display: table-cell; min-width: 70px">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.scientific_name")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.references") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.institution_code")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.owner_institution_code"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.basis_of_record")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.information_withheld"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.data_generalizations"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.dynamic_properties")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.catalog_number") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.occurrence_remarks")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.record_number") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.recorded_by") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.individual_count")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.sex") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.life_stage") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.reproductive_condition"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.behavior") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.establishment_means"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.occurrence_status")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.preparations") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.disposition") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.other_catalog_numbers"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.previous_identifications"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.associated_media")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.associated_references"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.associated_occurrences"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.associated_sequences"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.associated_taxa")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.sampling_protocol")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.sampling_effort")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.event_date") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.event_time") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.year") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.month") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.day") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.habitat") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.field_number") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.field_notes") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.event_remarks") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.continent") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.water_body") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.island_group") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.island") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.country") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.region_id") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.state_province") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.county") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.municipality") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.protected_area_id")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.locality") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.verbatim_elevation")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.minimum_elevation_in_meters"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.maximum_elevation_in_meters"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.verbatim_depth") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.minimum_depth_in_meters"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.maximum_depth_in_meters"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.minimum_distance_above_surface_in_meters"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.maximum_distance_above_surface_in_meters"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.location_according_to"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.location_remarks")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.decimal_latitude")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.decimal_longitude")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.geodetic_datum") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.coordinate_uncertainty_in_meters"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.coordinate_precision"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.point_radius_spatialfit"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.footprint_wkt") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.footprint_srs") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.footprint_spatial_fit"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.georeferenced_by")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.georeferenced_date")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.georeference_protocol"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.georeference_sources"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.georeference_verification_status"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.georeference_remarks"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.geological_context_id"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.earliest_eon_or_lowest_eonothem"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.latest_eon_or_highest_eonothem"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.earliest_era_or_lowest_erathem"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.latest_era_or_highest_erathem"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.earliest_period_or_lowest_system"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.latest_period_or_highest_system"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.earliest_epoch_or_lowest_series"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.latest_epoch_or_highest_series"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.earliest_age_or_lowest_stage"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.latest_age_or_highest_stage"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.lowest_biostratigraphic_zone"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.highest_biostratigraphic_zone"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.lithostratigraphic_terms"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.group") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.formation") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.member") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.bed") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.identified_by") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.date_identified")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.identification_references"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.identification_verification_status"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.identification_remarks"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.identification_qualifier"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.type_status") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.scientific_name_style"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.name_according_to")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.name_published_in")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.name_published_in_year"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.higher_classification"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.kingdom") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.phylum") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.class") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.order") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.family") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.genus") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.specific_epithet")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.infraspecific_epithet"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.taxon_rank") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.scientific_name_authorship"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.vernacular_name")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.nomenclatural_code")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.taxonomic_status")
                    }}
                  </th>

                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.diameter") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.height") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.health") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.health_diagnosis")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.sampling_method")
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.specimen_preservation"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.water_temp") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.weather") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.ph_water") }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.ph_soil") }}
                  </th>
                  <th style="display: table-cell">
                    {{
                    $t("nbds_lang.darwin_core_occurrences.other_parameter")
                    }}
                  </th>

                  <th style="display: table-cell">
                    {{
                    $t(
                    "nbds_lang.darwin_core_occurrences.nomenclatural_status"
                    )
                    }}
                  </th>
                  <th style="display: table-cell">
                    {{ $t("nbds_lang.darwin_core_occurrences.taxon_remarks") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in detailoccurrences" v-if="detailoccurrences" style="cursor: context-menu">
                  <td>
                    <span class="text-center-td">{{
                    (page - 1) * 50 + index + 1
                    }}</span>
                  </td>
                  <td>
                    <div style="white-space: initial">
                      {{ item.darwin_core_taxon.scientific_name }}
                    </div>
                  </td>
                  <td>{{ item.references }}</td>
                  <td>{{ item.institution_code }}</td>
                  <td>{{ item.owner_institution_code }}</td>
                  <td>{{ item.basis_of_record }}</td>
                  <td>{{ item.information_withheld }}</td>
                  <td>{{ item.data_generalizations }}</td>
                  <td>{{ item.dynamic_properties }}</td>
                  <td>{{ item.catalog_number }}</td>
                  <td>{{ item.occurrence_remarks }}</td>
                  <td>{{ item.record_number }}</td>
                  <td>{{ item.recorded_by }}</td>
                  <td>{{ item.individual_count }}</td>
                  <td>{{ item.sex }}</td>
                  <td>{{ item.life_stage }}</td>
                  <td>{{ item.reproductive_condition }}</td>
                  <td>{{ item.behavior }}</td>
                  <td>{{ item.establishment_means }}</td>
                  <td>{{ item.occurrence_status }}</td>
                  <td>{{ item.preparations }}</td>
                  <td>{{ item.disposition }}</td>
                  <td>{{ item.other_catalog_numbers }}</td>
                  <td>{{ item.previous_identifications }}</td>
                  <td>{{ item.associated_media }}</td>
                  <td>{{ item.associated_references }}</td>
                  <td>{{ item.associated_occurrences }}</td>
                  <td>{{ item.associated_sequences }}</td>
                  <td>{{ item.associated_taxa }}</td>
                  <td>{{ item.sampling_protocol }}</td>
                  <td>{{ item.sampling_effort }}</td>
                  <td>{{ item.event_date }}</td>
                  <td>{{ item.event_time }}</td>
                  <td>{{ item.year }}</td>
                  <td>{{ item.month }}</td>
                  <td>{{ item.day }}</td>
                  <td>{{ item.habitat }}</td>
                  <td>{{ item.field_number }}</td>
                  <td>{{ item.field_notes }}</td>
                  <td>{{ item.event_remarks }}</td>
                  <td>{{ item.continent }}</td>
                  <td>{{ item.water_body }}</td>
                  <td>{{ item.island_group }}</td>
                  <td>{{ item.island }}</td>
                  <td>{{ item.country }}</td>
                  <td>{{ item.region.name_vietnamese }}</td>
                  <td>{{ item.province.name_vietnamese }}</td>
                  <td>{{ item.district.name_vietnamese }}</td>
                  <td>{{ item.municipality }}</td>
                  <td>{{ item.protected_area.name }}</td>
                  <td>{{ item.locality }}</td>
                  <td>{{ item.verbatim_elevation }}</td>
                  <td>{{ item.minimum_elevation_in_meters }}</td>
                  <td>{{ item.maximum_elevation_in_meters }}</td>
                  <td>{{ item.verbatim_depth }}</td>
                  <td>{{ item.minimum_depth_in_meters }}</td>
                  <td>{{ item.maximum_depth_in_meters }}</td>
                  <td>{{ item.minimum_distance_above_surface_in_meters }}</td>
                  <td>{{ item.maximum_distance_above_surface_in_meters }}</td>
                  <td>{{ item.location_according_to }}</td>
                  <td>{{ item.location_remarks }}</td>
                  <td>{{ item.decimal_latitude }}</td>
                  <td>{{ item.decimal_longitude }}</td>
                  <td>{{ item.geodetic_datum }}</td>
                  <td>{{ item.coordinate_uncertainty_in_meters }}</td>
                  <td>{{ item.coordinate_precision }}</td>
                  <td>{{ item.point_radius_spatialfit }}</td>
                  <td>{{ item.footprint_wkt }}</td>
                  <td>{{ item.footprint_srs }}</td>
                  <td>{{ item.footprint_spatial_fit }}</td>
                  <td>{{ item.georeferenced_by }}</td>
                  <td>{{ item.georeferenced_date }}</td>
                  <td>{{ item.georeference_protocol }}</td>
                  <td>{{ item.georeference_sources }}</td>
                  <td>{{ item.georeference_verification_status }}</td>
                  <td>{{ item.georeference_remarks }}</td>
                  <td>{{ item.geological_context_id }}</td>
                  <td>{{ item.earliest_eon_or_lowest_eonothem }}</td>
                  <td>{{ item.latest_eon_or_highest_eonothem }}</td>
                  <td>{{ item.earliest_era_or_lowest_erathem }}</td>
                  <td>{{ item.latest_era_or_highest_erathem }}</td>
                  <td>{{ item.earliest_period_or_lowest_system }}</td>
                  <td>{{ item.latest_period_or_highest_system }}</td>
                  <td>{{ item.earliest_epoch_or_lowest_series }}</td>
                  <td>{{ item.latest_epoch_or_highest_series }}</td>
                  <td>{{ item.earliest_age_or_lowest_stage }}</td>
                  <td>{{ item.latest_age_or_highest_stage }}</td>
                  <td>{{ item.lowest_biostratigraphic_zone }}</td>
                  <td>{{ item.highest_biostratigraphic_zone }}</td>
                  <td>{{ item.lithostratigraphic_terms }}</td>
                  <td>{{ item.group }}</td>
                  <td>{{ item.formation }}</td>
                  <td>{{ item.member }}</td>
                  <td>{{ item.bed }}</td>
                  <td>{{ item.identified_by }}</td>
                  <td>{{ item.date_identified }}</td>
                  <td>{{ item.identification_references }}</td>
                  <td>{{ item.identification_verification_status }}</td>
                  <td>{{ item.identification_remarks }}</td>
                  <td>{{ item.identification_qualifier }}</td>
                  <td>{{ item.type_status }}</td>
                  <td>{{ item.darwin_core_taxon.scientific_name_style }}</td>
                  <td>{{ item.darwin_core_taxon.name_according_to }}</td>
                  <td>{{ item.darwin_core_taxon.name_published_in }}</td>
                  <td>{{ item.darwin_core_taxon.name_published_in_year }}</td>
                  <td>{{ item.darwin_core_taxon.higher_classification }}</td>
                  <td>{{ item.darwin_core_taxon.king_dom.name }}</td>
                  <td>{{ item.darwin_core_taxon.phylum.name }}</td>
                  <td>{{ item.darwin_core_taxon.class.name }}</td>
                  <td>{{ item.darwin_core_taxon.order.name }}</td>
                  <td>{{ item.darwin_core_taxon.family.name }}</td>
                  <td>{{ item.darwin_core_taxon.genus.name }}</td>
                  <td>{{ item.darwin_core_taxon.specific_epithet }}</td>
                  <td>{{ item.darwin_core_taxon.infraspecific_epithet }}</td>
                  <td>{{ item.darwin_core_taxon.taxon_rank }}</td>
                  <td>
                    {{ item.darwin_core_taxon.scientific_name_authorship }}
                  </td>
                  <td>{{ item.darwin_core_taxon.vernacular_name }}</td>
                  <td>{{ item.darwin_core_taxon.nomenclatural_code }}</td>
                  <td>{{ item.darwin_core_taxon.taxonomic_status }}</td>

                  <td>{{ item.darwin_core_taxon.diameter }}</td>
                  <td>{{ item.darwin_core_taxon.height }}</td>
                  <td>{{ item.darwin_core_taxon.health }}</td>
                  <td>{{ item.darwin_core_taxon.health_diagnosis }}</td>
                  <td>{{ item.darwin_core_taxon.sampling_method }}</td>
                  <td>{{ item.darwin_core_taxon.specimen_preservation }}</td>
                  <td>{{ item.darwin_core_taxon.temperature }}</td>
                  <td>{{ item.darwin_core_taxon.water_temp }}</td>
                  <td>{{ item.darwin_core_taxon.weather }}</td>

                  <td>{{ item.darwin_core_taxon.ph_water }}</td>
                  <td>{{ item.darwin_core_taxon.ph_soil }}</td>
                  <td>{{ item.darwin_core_taxon.other_parameter }}</td>
                  <td>{{ item.darwin_core_taxon.nomenclatural_status }}</td>
                  <td>{{ item.darwin_core_taxon.taxon_remarks }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <tr v-if="count > 1" class="footable-paging">
        <td colspan="6">
          <paginate v-model="page" :page-count="this.count" :page-range="3" :margin-pages="2"
            :click-handler="clickCallback" :prev-text="'‹‹'" :next-text="'››'" :container-class="'pagination'"
            :page-class="'page-item'"></paginate>
        </td>
      </tr>
      <!-- .left-aside-column-->

      <!-- /.left-right-aside-column-->
    </div>
  </div>
</template>

<script>
import * as env from "../../../../env.js";
import * as routes from "../../../../routes.js";
import { endpoint, endpointhttp } from "../../../../env.js";

export default {
  props: ["value"],
  data: function () {
    return {
      arrayLoi: [],
      confirmPopUp: false,
      typeNotification: null,
      textNotification: null,
      resetData: false,
      detailoccurrences: null,
      page: 1,
      count: 0,
      per_page: 0,
      current_page: 0,
      loading: false,
      file: "",
      field_excel: {
        Dòng: "dong",
        Lỗi: "loi",
      },
    };
  },
  mounted() {
    this.getDatasetDetail();
  },
  methods: {
    goback(){
      window.location="/admin/dataset"
    },
    getDatasetDetail() {
      this.loading = true;
      this.page = 1;
      this.resetData = false;
      axios
        .get(
          env.endpointhttp +
          "admin/dataset/detail/occurrences/info/" +
          this.value
        )
        .then((response) => {
          this.detailoccurrences = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
          this.loading = false;
        });
    },
    downloadTemplate() {
      axios
        .get(env.endpointhttp + "download/template/occurrences", {
          responseType: "blob",
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "file_mau_occurrences.xlsx");
          document.body.appendChild(link);
          link.click();
        });
    },
    uploadFile() {
      this.file = this.$refs.file.files[0];
      let formData = new FormData();
      formData.append("file", this.file);
      axios
        .post(
          env.endpointhttp + "admin/dataset/occurrences/upload/" + this.value,
          formData
        )
        .then((response) => {
          if (response.data.message == "message.success") {
            this.arrayLoi = response.data.result;
            this.typeNotification = 2;
            this.textNotification = "Cập nhật thành công.";
            this.getDatasetDetail();
          } else {
            this.typeNotification = 1;
            this.textNotification = "Cập nhật không thành công.";
          }
        })
        .catch((error) => {
          this.typeNotification = 1;
          this.textNotification = "Cập nhật không thành công.";
        })
        .finally(() => {
          this.loading = false;
          this.typeNotification = null;
          this.textNotification = null;
        });
    },
    clickCallback(pageNum) {
      this.page = pageNum;
      axios
        .get(
          env.endpointhttp +
          "admin/dataset/detail/occurrences/info/" +
          this.value,
          {
            params: {
              page: this.page,
            },
          }
        )
        .then((response) => {
          this.detailoccurrences = response.data.result.data;
          this.count = response.data.result.last_page;
          this.per_page = response.data.result.per_page;
          this.current_page = response.data.result.current_page;
        });
    },
  },
};
</script>