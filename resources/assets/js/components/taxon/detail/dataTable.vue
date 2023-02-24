<template>
  <div class="row scrollable">
    <div class="col-md-6">
      <section class="term-block is-collapsed">
        <div class="term-block__header">
          <h4>
            {{
              $t(
                "nbds_lang.darwin_core_taxon.species_defining_characteristics.title"
              )
            }}
          </h4>
        </div>
        <div class="term-block__terms">
          <table class="table table--compact">
            <thead>
              <tr>
                <th>Term</th>
                <th>Interpreted</th>
                <th>Original</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.taxon_concept"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.taxon_concept_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.scientific_name_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.scientific_name_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.accepted_name_usage_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.accepted_name_usage_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.parent_name_usage_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.parent_name_usage_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.original_name_usage_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.original_name_usage_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.taxon_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.taxon_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.name_published_in_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.name_published_in_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.species_defining_characteristics.taxon_concept_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.taxon_concept_id }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <section class="term-block is-collapsed">
        <div class="term-block__header">
          <h4>{{ $t("nbds_lang.darwin_core_taxon.native_name.title") }}</h4>
        </div>
        <div class="term-block__terms">
          <table class="table table--compact">
            <thead>
              <tr>
                <th>Term</th>
                <th>Interpreted</th>
                <th>Original</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.native_name.vernacular_name"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.vernacular_name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.native_name.language") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.language }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      <section class="term-block is-collapsed">
        <div class="term-block__header">
          <h4>{{ $t("nbds_lang.darwin_core_taxon.more_information") }}</h4>
        </div>
        <div class="term-block__terms">
          <table class="table table--compact">
            <thead>
              <tr>
                <th>Term</th>
                <th>Interpreted</th>
                <th>Original</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              <tr class="isDifferent">
                <td>{{ $t("nbds_lang.use") }}</td>
                <td></td>
                <td colspan="2">
                  {{
                    data.nbds_taxon_extension[0]
                      ? data.nbds_taxon_extension[0].use
                      : null
                  }}
                </td>
              </tr>
              <tr class="isDifferent">
                <td>{{ $t("nbds_lang.description") }}</td>
                <td></td>
                <td colspan="2">
                  {{
                    data.nbds_taxon_extension[0]
                      ? data.nbds_taxon_extension[0].morphological_description
                      : null
                  }}
                </td>
              </tr>
              <tr class="isDifferent">
                <td>{{ $t("nbds_lang.datasets") }}</td>
                <td></td>
                <td colspan="2">
                  {{ data.data_resource ? data.data_resource.title : null }}
                </td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.nbds_taxon_extensions.environmentally_sensitive"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">
                  {{
                    data.nbds_taxon_extension[0]
                      ? data.nbds_taxon_extension[0].environmentally_sensitive
                      : null
                  }}
                </td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.nbds_taxon_extensions.rich_and_rare") }}
                </td>
                <td></td>
                <td colspan="2">
                  {{
                    data.nbds_taxon_extension[0]
                      ? data.nbds_taxon_extension[0].rich_and_rare
                      : null
                  }}
                </td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.nbds_taxon_extensions.distribution_status_in_vietnam"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">
                  {{
                    data.nbds_taxon_extension[0]
                      ? data.nbds_taxon_extension[0].distribution_in_vietnam
                      : null
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      <section class="term-block is-collapsed">
        <div class="term-block__header">
          <!-- <h4>{{ $t("nbds_lang.darwin_core_taxon.native_name.title") }}</h4> -->
          <h4>Môi trường sống</h4>
        </div>
        <div class="term-block__terms">
          <table class="table table--compact">
            <thead>
              <tr>
                <th>Term</th>
                <!-- <th>Interpreted</th>
                <th>Original</th>
                <th>Remarks</th> -->
              </tr>
            </thead>
            <tbody>
              <tr class="isDifferent">
                <td>
                  {{
                    data.nbds_taxon_extension[0]
                      ? data.nbds_taxon_extension[0].habitat
                      : null
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
    <div class="col-md-6">
      <section class="term-block is-collapsed">
        <div class="term-block__header">
          <h4>{{ $t("nbds_lang.darwin_core_taxon.information.title") }}</h4>
        </div>
        <div class="term-block__terms">
          <table class="table table--compact">
            <thead>
              <tr>
                <th>Term</th>
                <th>Interpreted</th>
                <th>Interpreted</th>
                <th>Interpreted</th>
              </tr>
            </thead>
            <tbody>
              <tr class="isDifferent">
                <td>
                  {{
                    $t("nbds_lang.darwin_core_taxon.information.science_name")
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.scientific_name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.accepted_name_usage"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.accepted_name_usage }}</td>
              </tr>

              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.name_published_in"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.name_published_in }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.name_published_in_year"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.name_published_in_year }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.verbatim_taxon_rank"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.verbatim_taxon_rank }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.kingdom") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.king_dom.name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.phylums") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.phylum.name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.class") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.class.name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.order") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.order.name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.family") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.family.name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.genus") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.genus.name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.subgenus") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.subgenus }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.species") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.species.name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t("nbds_lang.darwin_core_taxon.information.sub_species_id")
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.sub_species_id }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{ $t("nbds_lang.darwin_core_taxon.information.taxon_rank") }}
                </td>
                <td></td>
                <td colspan="2">{{ data.taxon_rank }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.scientific_name_authorship"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.scientific_name_authorship }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.parent_name_usage"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.parent_name_usage }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.vernacular_name"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.vernacular_name }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.taxonomic_status"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.taxonomic_status }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.nomenclatural_status"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.nomenclatural_status }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t("nbds_lang.darwin_core_taxon.information.taxon_remarks")
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.taxon_remarks }}</td>
              </tr>
              <tr class="isDifferent">
                <td>
                  {{
                    $t(
                      "nbds_lang.darwin_core_taxon.information.scientific_name_id"
                    )
                  }}
                </td>
                <td></td>
                <td colspan="2">{{ data.scientific_name_id }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      <section class="term-block is-collapsed">
        <div class="term-block__header">
          <!-- <h4>{{ $t("nbds_lang.darwin_core_taxon.native_name.title") }}</h4> -->
          <h4>Nguồn gốc địa phương</h4>
        </div>
        <div class="term-block__terms">
          <table class="table table--compact">
            <thead>
              <tr>
                <th>Term</th>
                <!-- <th>Interpreted</th>
                <th>Original</th>
                <th>Remarks</th> -->
              </tr>
            </thead>
            <tbody>
              <tr class="isDifferent">
                <td>
                  {{
                    data.nbds_taxon_extension[0]
                      ? data.nbds_taxon_extension[0].phan_bo_ha_noi
                      : null
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</template>
<script>
export default {
  props: ["data"],
  data: () => ({
    item: null,
  }),
  created() {
    if (this.data) {
      console.log(1,this.data)
      this.item = this.data;
    }
  },
  methods: {},
};
</script>
