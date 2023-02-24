export const info = [
  // {
  //   label: "Đoàn thanh tra",
  //   field: "doan_thanh_tra_id",
  //   type: "select",
  //   items: "doan_thanh_tra",
  //   display: "so_quyet_dinh",
  // },
  {
    label: "Số văn bản",
    field: "so_van_ban",
    type: "text",
  },
  {
    label: "Ngày ban hành",
    field: "ngay_thong_bao",
    type: "date",
  },

  {
    label: "Thành phần làm việc của địa phương",
    field: "thanh_phan",
    type: "text",
  },
  {
    label: "Tình hình ban hành VBPL",
    field: "ban_hanh_vbpl",
    type: "text",
  },
  {
    label: "Tình hình quản lý đa sạng sinh học",
    field: "quan_ly_ddsh",
    type: "text",
  },
];

export const result = [
  {
    id: 1,
    group: "Công tác điều tra khảo sát, xây dựng csdl",
    children: [
      {
        label: "Địa chỉ đơn vị quản lý cơ sở",
        field: "dieu_tra_khao_sat",
        type: "boolean",
      },
      {
        label: "Thu thập gen",
        field: "thu_thap_gen",
        type: "boolean",
      },
      {
        label: "Chia sẻ nguồn gen",
        field: "chia_se_nguon_gen",
        type: "boolean",
      },
      {
        label: "Điều tra, thống kê SVNL",
        field: "dieu_tra_svnl",
        type: "boolean",
      },
      {
        label: "Diện tích chuyển đổi mục đích",
        field: "dien_tich_cdmd",
        type: "number",
      },
      {
        label: "Thời gian",
        field: "thoi_gian_csdl",
        type: "boolean",
      },
      {
        label: "Công tác giao khoán rừng",
        field: "giao_khoan_rung",
        type: "boolean",
      },
      {
        label: "Công tác chi trả DVMT",
        field: "chi_tra_dvmt",
        type: "boolean",
      },
      {
        label: "Du lịch sinh thai",
        field: "du_lich_st",
        type: "boolean",
      },
      {
        label: "Hoạt động đầu tư phát triển",
        field: "hd_dau_tu",
        type: "text",
      },
      {
        label: "Hoạt động đánh giá ĐTM",
        field: "dtm",
        type: "text",
      },
      {
        label: "Lập báo cáo hiện trạng DDSH",
        field: "bao_cao_hien_trang",
        type: "boolean",
      },
      {
        label: "Xây dựng CSDL DDSH",
        field: "xdcsdl",
        type: "boolean",
      },
    ],
  },
  {
    id: 2,
    group: "Công tác bảo tồn",
    children: [
      {
        label: "Công tác nhân nuôi, tái thả các loài",
        field: "nhan_nuoi_tai_tha",
        type: "text",
      },
      {
        label: "Công tác cứu hộ các loài",
        field: "cuu_ho",
        type: "text",
      },
      {
        label: "Thiết lập vùng đêm",
        field: "tl_vung_dem",
        type: "boolean",
      },
      {
        label: "Thiết lập vườn thực vật",
        field: "tl_vuon_thuc_vat",
        type: "boolean",
      },
      {
        label: "Thiết lập trung tâm cứu hộ",
        field: "tl_trung_tam_cuu_ho",
        type: "boolean",
      },
    ],
  },
  {
    id: 3,
    group: "Công tác xử lý vi phạm",
    children: [
      {
        label: "Số vụ khai thác lâm sản, săn bắt thú",
        field: "so_vu_kt",
        type: "number",
      },
      {
        label: "Thời gian",
        field: "thoi_gian_xu_ly_vi_pham",
        type: "text",
      },
      {
        label: "Nội dung vi phạm",
        field: "noi_dung_vi_pham",
        type: "text",
      },
      {
        label: "Tang vật thu được",
        field: "tang_vat_thu_duoc",
        type: "text",
      },
      {
        label: "Số vụ VPHC",
        field: "so_vu_vphc",
        type: "number",
      },
      {
        label: "Tổng tiền phạt",
        field: "tong_tien_phat",
        type: "number",
      },
      {
        label: "Số vụ hình sự",
        field: "so_vu_hinh_su",
        type: "number",
      },
      {
        label: "Lưu hồ sơ",
        field: "luu_ho_so",
        type: "boolean",
      },
    ],
  },
  {
    id: 4,
    group: "Đánh giá những nội dung đã đạt được và những hạn chế",
    children: [
      {
        label: "Nội dung đã thực hiện",
        field: "noi_dung_da_thuc_hien",
        type: "text",
      },
      {
        label: "Nội dung chưa thực hiện",
        field: "noi_dung_chua_thuc_hien",
        type: "text",
      },
      {
        label: "Các tồn tại hạn chế tại cơ sở",
        field: "ton_tai_han_che",
        type: "text",
      },
      {
        label: "Các khó khăn bất cập",
        field: "kho_khan",
        type: "text",
      },
      {
        label: "Nguyên nhân",
        field: "nguyen_nhan",
        type: "text",
      },
      {
        label: "Yêu cầu của đoàn TT",
        field: "yeu_cau",
        type: "text",
      },
      {
        label: "Nội dung kiến nghị của đoàn",
        field: "kien_nghi_doan_tt",
        type: "text",
      },
      {
        label: "Kiến nghị của cơ sở",
        field: "kien_nghi_co_so",
        type: "text",
      },
    ],
  },
  {
    id: 5,
    group: "Kết quả quan trắc",
    children: [
      {
        label: "Số mẫu",
        field: "so_mau",
        type: "number",
      },
      {
        label: "Địa điểm lấy mẫu",
        field: "dia_diem_lay_mau",
        type: "text",
      },
      {
        label: "Loại mẫu",
        field: "loai_mau_mt",
        type: "text",
      },
      {
        label: "Kết quả phân tích mẫu",
        field: "kq_phan_tich_mau",
        type: "text",
      },
      {
        label: "Thông số vượt chuẩn",
        field: "thong_so_vuot_chuan",
        type: "text",
      },
      {
        label: "Số lần vượt",
        field: "so_lan_vuot",
        type: "text",
      },
      {
        label: "Quy chuẩn áp dụng",
        field: "quy_chuan_ap_dung",
        type: "text",
      },
      {
        label: "Cơ sở xả thải",
        field: "co_so_xa_thai",
        type: "text",
      },
    ],
  },
  {
    id: 6,
    group: "Vi Phạm",
    children: [
      {
        label: "Vi phạm",
        field: "vi_pham",
        type: "boolean",
      },
      {
        label: "Nội dung vi phạm",
        field: "noi_dung_vi_pham_1",
        type: "text",
      },
    ],
  },
  {
    id: 7,
    group: "Quyết định xử phạt",
    children: [
      {
        label: "Quyết định xử phạt",
        field: "qd_xu_phat",
        type: "boolean",
      },
      {
        label: "Số QĐ xử phạt",
        field: "so_hieu",
        type: "text",
      },
      {
        label: "Cơ quan quyết định",
        field: "cq_ban_hanh",
        type: "",
      },
      {
        label: "Thời gian quyết định",
        field: "ngay_ban_hanh",
        type: "date",
      },
      {
        label: "Quyết định sửa đổi",
        field: "qd_sua_doi",
        type: "text",
      },
      {
        label: "Thời gian ký QĐ sửa đổi",
        field: "thoi_gian_sua",
        type: "date",
      },
      {
        label: "Hình thức xử phạt chính",
        field: "hinh_thuc_xp_chinh",
        type: "text",
      },
      {
        label: "Xử phạt bổ sung",
        field: "xu_phat_bo_sung",
        type: "text",
      },
      {
        label: "Số tiền phạt",
        field: "so_tien_phat",
        type: "number",
      },
      {
        label: "Biên lai nộp phạt",
        field: "bien_lai_nop_phat",
        type: "text",
      },
      {
        label: "Biện pháp khắc phục",
        field: "bien_phap_khac_phu",
        type: "text",
      },
      {
        label: "Nội dung khác phục",
        field: "noi_dung_khac_phuc",
        type: "text",
      },
      {
        label: "Kết quả khắc phục",
        field: "ket_qua_khac_phuc",
        type: "text",
      },
      {
        label: "Số báo cáo khắc phục",
        field: "so_bao_cao_kp",
        type: "text",
      },
      {
        label: "Ý kiến phản hồi",
        field: "y_kien_phan_hoi",
        type: "",
      },
      {
        label: null,
        field: "files_xu_phat",
        type: "file",
      },
    ],
  },
];
