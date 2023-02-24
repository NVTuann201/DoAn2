export const fields_Image = [
  {
    label: "Tham khảo",
    type: "text",
    required: true,
    field: "references",
    class: "col-12",
  },
  {
    label: "Tiêu đề",
    type: "text",
    field: "title",
    class: "col-12",
  },
  {
    label: "Mô tả",
    type: "text",
    field: "description",
    class: "col-12",
  },
  {
    label: "Kinh độ",
    type: "text",
    field: "longitude",
    class: "col-12",
  },
  {
    label: "Vĩ độ",
    type: "text",
    field: "latitude",
    class: "col-12",
  },
  {
    label: "Thời gian tạo",
    type: "date",
    field: "created",
    class: "col-12",
  },
  {
    label: "Tác giả",
    type: "text",
    field: "creator",
    class: "col-12",
  },
  {
    label: "Người đóng góp",
    type: "text",
    field: "contributor",
    class: "col-12",
  },
  {
    label: "Nhà xuất bản",
    type: "text",
    field: "publisher",
    class: "col-12",
  },
  {
    label: "Độc giả/người dùng",
    type: "text",
    field: "audience",
    class: "col-12",
  },
  {
    label: "Giấy phép",
    type: "text",
    field: "license",
    class: "col-12",
  },
  {
    label: "Chủ sở hữu",
    type: "text",
    field: "rights_holder",
    class: "col-12",
  },
  {
    label: "Liên quan khác",
    type: "text",
    field: "other_relation",
    class: "col-12",
  },
  {
    label: "Đã công bố",
    type: "boolean",
    field: "public",
    required: true,
    class: "col-12",
  },
];
export const fields_PhatHienLoai = [
  {
    label: "Kiểu (loại dữ liệu)",
    type: "text",
    field: "type",
  },
  {
    label: "Ngày cập nhật",
    type: "date",
    field: "modified",
  },
  {
    label: "Ngôn ngữ",
    type: "text",
    field: "language",
  },
  {
    label: "Quyền",
    type: "text",
    field: "rights",
  },
  {
    label: "Người nắm quyền",
    type: "text",
    field: "rights_holder",
  },
  {
    label: "Quyền truy cập",
    type: "text",
    field: "access_rights",
  },
  {
    label: "Trích dẫn thư mục",
    type: "text",
    field: "bibliographic_citation",
  },
  {
    label: "Nguồn tham khảo",
    type: "text",
    field: "references",
  },
  {
    label: "Định danh trụ sở",
    type: "text",
    field: "institution_id",
  },
  {
    label: "Định danh bộ sưu tầm",
    type: "text",
    field: "collection_id",
  },
  // {
  //   label: "Định danh bộ dữ liệu",
  //   type: "search",
  //   field: "dataset_resource_id",
  // },
  {
    label: "Mã trụ sở",
    type: "text",
    field: "institution_code",
  },
  {
    label: "Mã bộ sưu tập",
    type: "text",
    field: "collection_code",
  },
  {
    label: "Mã trụ sở sở hữu",
    type: "text",
    field: "owner_institution_code",
  },
  {
    label: "Căn cứ bản ghi",
    type: "text",
    field: "basis_of_record",
    required: true,
  },
  {
    label: "Thông tin giữ lại",
    type: "text",
    field: "information_withheld",
  },
  {
    label: "Tổng quát",
    type: "text",
    field: "data_generalizations",
  },
  {
    label: "Tính động",
    type: "text",
    field: "dynamic_properties",
  },
];
export const fields_ThongTinSuXuatHien = [
  {
    label: "Định danh hiện trường",
    type: "text",
    field: "site_identification",
  },
  {
    label: "Số danh mục",
    type: "text",
    field: "catalog_number",
  },
  {
    label: "Lưu ý",
    type: "text",
    field: "occurrence_remarks",
  },
  {
    label: "Số bản ghi",
    type: "text",
    field: "record_number",
  },
  {
    label: "Người thu thập",
    type: "text",
    field: "recorded_by",
  },
  {
    label: "Định danh cá nhân",
    type: "text",
    field: "individual_id",
  },
  {
    label: "Số người có mặt",
    type: "text",
    field: "individual_count",
  },
  {
    label: "Giới tính sinh vật",
    type: "text",
    field: "sex",
  },
  {
    label: "Đường kính ngang ngực",
    type: "text",
    field: "diameter",
  },
  {
    label: "Chiều cao",
    type: "text",
    field: "height",
  },
  {
    label: "Giai đoạn sống",
    type: "text",
    field: "life_stage",
  },
  {
    label: "Điều kiện sức khỏe",
    type: "text",
    field: "health",
  },
  {
    label: "Điều kiện sinh sản",
    type: "text",
    field: "reproductive_condition",
  },
  {
    label: "Chẩn đoán sức khỏe",
    type: "text",
    field: "health_diagnosis",
  },
  {
    label: "Hành vi",
    type: "text",
    field: "behavior",
  },
  {
    label: "Ý nghĩa thành lập",
    type: "text",
    field: "establishment_means",
  },
  {
    label: "Trạng thái hiện trường",
    type: "text",
    field: "occurrence_status",
  },
  {
    label: "Chuẩn bị",
    type: "text",
    field: "preparations",
  },
  {
    label: "Sắp đặt",
    type: "text",
    field: "disposition",
  },
  {
    label: "Số danh mục khác",
    type: "text",
    field: "other_catalog_numbers",
  },
  {
    label: "Định danh trước đó",
    type: "text",
    field: "previous_identifications",
  },
  {
    label: "Truyền thông phối hợp",
    type: "text",
    field: "associated_media",
  },
  {
    label: "Tài liệu tham khảo phối hợp",
    type: "text",
    field: "associated_references",
  },
  {
    label: "Dữ liệu hiện trường phối hợp",
    type: "text",
    field: "associated_occurrences",
  },
  {
    label: "Chuỗi liên kết",
    type: "text",
    field: "associated_sequences",
  },
  {
    label: "Phân loại liên quan",
    type: "text",
    field: "associated_taxa",
  },
];
export const fields_ThongTinSuKien = [
  // {
  //   label: "Định danh địa điểm",
  //   type: "text",
  //   field: "event_id",
  // },
  {
    label: "Quy chuẩn lấy mẫu",
    type: "text",
    field: "sampling_protocol",
  },
  {
    label: "Nỗ lực lấy mẫu",
    type: "text",
    field: "sampling_effort",
  },
  {
    label: "Phương pháp lấy mẫu",
    type: "text",
    field: "sampling_method",
  },
  {
    label: "Phương pháp bảo quản",
    type: "text",
    field: "specimen_preservation",
  },
  {
    label: "Ngày phát hiện loài",
    type: "date",
    field: "event_date",
  },
  {
    label: "Thời điểm phát hiện loài",
    type: "time",
    field: "event_time",
  },
  {
    label: "Ngày bắt đầu của năm diễn ra sự kiện",
    type: "number",
    field: "start_day_of_year",
  },
  {
    label: "Ngày cuối cùng của năm diễn ra sự kiện",
    type: "number",
    field: "end_day_of_year",
  },
  {
    label: "Năm thu thập",
    type: "number",
    field: "year",
  },
  {
    label: "Tháng thu thập",
    type: "number",
    field: "month",
  },
  {
    label: "Ngày thu thập",
    type: "number",
    field: "day",
  },
  {
    label: "Ngày sự kiện nguyên văn",
    type: "text",
    field: "verbatim_event_date",
  },
  {
    label: "Môi trường sống",
    type: "select",
    field: "habitat",
    meta: {
      items: [
        { value: 1, text: "Nước mặn" },
        { value: 2, text: "Nước lợ" },
        { value: 3, text: "Nước ngọt" },
        { value: 4, text: "Lưỡng cư" },
        { value: 5, text: "Trên cạn" },
      ],
    },
  },
  {
    label: "Số trường",
    type: "text",
    field: "field_number",
  },
  {
    label: "Ghi chú trường",
    type: "text",
    field: "field_notes",
  },
  {
    label: "Nhiệt độ",
    type: "text",
    field: "temperature",
  },
  {
    label: "Nhiệt độ nước",
    type: "text",
    field: "water_temp",
  },
  {
    label: "Thời tiết",
    type: "text",
    field: "weather",
  },
  {
    label: "Độ PH nước",
    type: "text",
    field: "ph_water",
  },
  {
    label: "Độ PH đất",
    type: "text",
    field: "ph_soil",
  },
  {
    label: "Thông số khác",
    type: "text",
    field: "other",
  },
  {
    label: "Lưu ý sự kiện",
    type: "text",
    field: "event_remarks",
  },
];
export const fields_ThongTinDiaDiem = [
  // {
  //   label: "Định danh địa điểm",
  //   type: "text",
  //   field: "location_id",
  // },
  // {
  //   label: "Định danh địa chất nâng cao",
  //   type: "text",
  //   field: "higher_geography_id",
  // },
  {
    label: "Địa chất nâng cao",
    type: "text",
    field: "higher_geography",
  },
  {
    label: "Nguồn nước",
    type: "text",
    field: "water_body",
  },
  {
    label: "Quận/Huyện",
    type: "text",
    field: "county",
  },
  {
    label: "Đô thị",
    type: "text",
    field: "municipality",
  },
  {
    label: "Vùng",
    type: "text",
    field: "locality",
  },
  {
    label: "Vùng theo nguyên văn",
    type: "text",
    field: "verbatim_locality",
  },
  {
    label: "Độ cao nguyên văn (m)",
    type: "text",
    field: "verbatim_elevation",
  },
  {
    label: "Độ cao tối thiểu (m)",
    type: "number",
    field: "minimum_elevation_in_meters",
    meta: { min: 0 },
  },
  {
    label: "Độ cao tối đa (m)",
    type: "number",
    field: "maximum_elevation_in_meters",
    meta: { min: 0 },
  },
  {
    label: "Độ sâu nguyên gốc (m)",
    type: "number",
    field: "verbatim_depth",
    meta: { min: 0 },
  },
  {
    label: "Độ sâu tối thiểu (m)",
    type: "number",
    field: "minimum_depth_in_meters",
    meta: { min: 0 },
  },
  {
    label: "Độ sâu tối đa (m)",
    type: "number",
    field: "maximum_depth_in_meters",
    meta: { min: 0 },
  },
  {
    label: "Khoảng cách tối thiểu trên bề mặt (m)",
    type: "number",
    field: "minimum_distance_above_surface_in_meters",
    meta: { min: 0 },
  },
  {
    label: "Khoảng cách tối đa trên bề mặt (m)",
    type: "number",
    field: "maximum_distance_above_surface_in_meters",
    meta: { min: 0 },
  },
  {
    label: "Thông tin về chuẩn địa điểm",
    type: "text",
    field: "location_according_to",
  },
  {
    label: "Lưu ý địa điểm",
    type: "text",
    field: "location_remarks",
  },
  {
    label: "Tọa độ nguyên gốc",
    type: "text",
    field: "verbatim_coordinates",
  },
  {
    label: "Vĩ độ nguyên gốc",
    type: "text",
    field: "verbatim_latitude",
  },
  {
    label: "Kinh độ nguyên gốc",
    type: "text",
    field: "verbatim_longitude",
  },
  {
    label: "Hệ tọa độ gốc",
    type: "text",
    field: "verbatim_coordinate_system",
  },
  {
    label: "SRS nguyên gốc",
    type: "text",
    field: "verbatim_srs",
  },
  {
    label: "Vĩ độ",
    type: "text",
    field: "decimal_latitude",
  },
  {
    label: "Kinh độ",
    type: "text",
    field: "decimal_longitude",
  },
  {
    label: "Tọa độ vuông góc",
    type: "text",
    field: "coordinate_uncertainty_in_meters",
  },
  {
    label: "Độ chính xác tọa độ",
    type: "text",
    field: "coordinate_precision",
  },
  {
    label: "Độ chồng khớp",
    type: "text",
    field: "point_radius_spatialfit",
  },
  {
    label: "Mô phỏng WKT",
    type: "text",
    field: "footprint_wkt",
  },
  {
    label: "Mô phỏng SRS",
    type: "text",
    field: "footprint_srs",
  },
  {
    label: "Độ chồng khớp mô phỏng",
    type: "text",
    field: "footprint_spatial_fit",
  },
  {
    label: "Người thiết lập tọa độ",
    type: "text",
    field: "georeferenced_by",
  },
  {
    label: "Ngày thiết lập tọa độ",
    type: "date",
    field: "georeferenced_date",
  },
  {
    label: "Định chuẩn thiết lập",
    type: "text",
    field: "georeference_protocol",
  },
  {
    label: "Nguồn tài liệu thiết lập tọa độ",
    type: "text",
    field: "georeference_sources",
  },
  {
    label: "Trạng thái kiểm định tọa độ thiết lập",
    type: "text",
    field: "georeference_verification_status",
  },
  {
    label: "Lưu ý thiết lập tọa độ",
    type: "text",
    field: "georeference_remarks",
  },
];
export const fields_BoiCanhDiaChat = [
  // {
  //   label: "Định danh bối cảnh địa chất",
  //   type: "text",
  //   field: "geological_context_id",
  // },
  {
    label: "Địa tầng thấp nhất",
    type: "text",
    field: "earliest_eon_or_lowest_eonothem",
  },
  {
    label: "Địa tầng cao nhất",
    type: "text",
    field: "latest_eon_or_highest_eonothem",
  },
  {
    label: "Kỷ nguyên sớm nhất",
    type: "text",
    field: "earliest_era_or_lowest_erathem",
  },
  {
    label: "Kỷ nguyên muộn nhất",
    type: "text",
    field: "latest_era_or_highest_erathem",
  },
  {
    label: "Thời ký sớm nhất",
    type: "text",
    field: "earliest_period_or_lowest_system",
  },
  {
    label: "Thời ký muộn nhất",
    type: "text",
    field: "latest_period_or_highest_system",
  },
  {
    label: "Thời đại sớm nhất",
    type: "text",
    field: "earliest_epoch_or_lowest_series",
  },
  {
    label: "Thời đại muộn nhất",
    type: "text",
    field: "latest_epoch_or_highest_series",
  },
  {
    label: "Tuổi sớm nhất",
    type: "text",
    field: "earliest_age_or_lowest_stage",
  },
  {
    label: "Tuổi muộn nhất",
    type: "text",
    field: "latest_age_or_highest_stage",
  },
  {
    label: "Sinh địa tầng thấp nhất",
    type: "text",
    field: "lowest_biostratigraphic_zone",
  },
  {
    label: "Sinh địa tầng cao nhất",
    type: "text",
    field: "highest_biostratigraphic_zone",
  },
  {
    label: "Thạch địa tầng",
    type: "text",
    field: "lithostratigraphic_terms",
  },
  {
    label: "Nhóm",
    type: "text",
    field: "group",
  },
  {
    label: "Loài",
    type: "text",
    field: "formation",
  },
  {
    label: "Phần tử",
    type: "text",
    field: "member",
  },
  {
    label: "Tên đầy đủ thạch địa tầng",
    type: "text",
    field: "bed",
  },
];
export const fields_DacTinhLoai = [
  {
    label: "Định danh khoa học",
    type: "text",
    field: "scientific_name_id",
  },
  {
    label: "Tên khoa học",
    type: "text",
    required: true,
    field: "scientific_name",
  },
  {
    label: "Tên đồng danh",
    type: "text",
    field: "scientific_name_style",
  },
  {
    label: "Tên chấp nhận",
    type: "text",
    field: "accepted_name_usage",
  },
  {
    label: "Tên cấp bậc gần nhất",
    type: "text",
    field: "parent_name_usage",
  },
  {
    label: "Tên gốc",
    type: "text",
    field: "original_name_usage",
  },
  {
    label: "Tài liệu gốc tham khảo",
    type: "text",
    field: "name_according_to",
  },
  {
    label: "Tên xuất bản",
    type: "text",
    field: "name_published_in",
  },
  {
    label: "Tên năm xuất bản",
    type: "text",
    field: "name_published_in_year",
  },
];
export const fields_DinhDanhLoai = [
  {
    label: "Định danh nhận dạng",
    type: "text",
    field: "identification_id",
  },
  {
    label: "Nhận dạng bởi",
    type: "text",
    field: "identified_by",
  },
  {
    label: "Ngày nhận dạng",
    type: "date",
    field: "date_identified",
  },
  {
    label: "Tài liệu tham khảo",
    type: "text",
    field: "identification_references",
  },
  {
    label: "Tình trạng kiểm định",
    type: "text",
    field: "identification_verification_status",
  },
  {
    label: "Lưu ý nhận dạng",
    type: "text",
    field: "identification_remarks",
  },
  {
    label: "Định tính nhận dạng",
    type: "text",
    field: "identification_qualifier",
  },
  {
    label: "Loại trạng thái",
    type: "text",
    field: "type_status",
  },
];
