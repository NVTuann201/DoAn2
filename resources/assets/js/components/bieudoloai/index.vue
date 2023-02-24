<template>
  <div class="container--desktop" style="padding-top: 80px">
    <div style="
        font-size: 22px;
        padding-bottom: 80px;
        text-align: center;
        font-weight: bold;
      ">
      BIỂU ĐỒ ĐA DẠNG SINH HỌC
    </div>
    <div style="width: 100%">
      <div class="d-flex">
        <div class="item-chart">
          <apexchart type="donut" width="400" :options="kingdomData.options" :series="kingdomData.series"></apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng giới</div>
        </div>
        <div>
          <apexchart width="440" type="donut" :options="doiTuong.options" :series="doiTuong.series"></apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng theo đối tượng</div>
        </div>
      </div>
      <div class="d-flex">
        <div>
          <apexchart width="460px" type="pie" :options="thucVat.options" :series="thucVat.series"></apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng loài thực vật</div>
        </div>
        <div>
          <apexchart width="420px" type="pie" :options="dongVat.options" :series="dongVat.series"></apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng loài động vật</div>
        </div>
      </div>
      <div class="d-flex">
        <div>
          <apexchart width="500" type="pie" :options="diaBan.options" :series="diaBan.series"></apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng theo địa bàn</div>
        </div>
      </div>
      <div class="d-flex">
        <div>
          <apexchart width="800" type="donut" :options="triThucTruyenThong.options" :series="triThucTruyenThong.series">
          </apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng theo tri thức truyền thông</div>
        </div>
      </div>
      <div class="d-flex">
        <div>
          <apexchart
            width="880"
            :options="heSinhThai"
            :series="heSinhThai.series"
          ></apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng theo hệ sinh thái</div>
        </div>
      </div>
      <div class="d-flex">
        <div>
          <apexchart width="750" type="pie" :options="noiLuuGius.options" :series="noiLuuGius.series"></apexchart>
          <div class="tenbieudo">Biểu đồ đa dạng nguồn gen theo nơi lưu giữ</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["dulieubieudo"],
  data: () => ({
    chart: {
      toolbar: {
        show: true,
        offsetX: 0,
        offsetY: 0,
        tools: {
          download: true,
          selection: true,
          zoom: true,
          zoomin: true,
          zoomout: true,
          pan: true,
          reset: true | '<img src="/static/icons/reset.png" width="20">',
          customIcons: [],
        },
        export: {
          csv: {
            filename: undefined,
            columnDelimiter: ",",
            headerCategory: "category",
            headerValue: "value",
            dateFormatter(timestamp) {
              return new Date(timestamp).toDateString();
            },
          },
          svg: {
            filename: undefined,
          },
          png: {
            filename: undefined,
          },
        },
        autoSelected: "zoom",
      },
    },
    kingdomData: {
      options: {
        colors:[],
        labels: [],
      },
      series: [],
    },
    doiTuong: {
      options: {
        labels: [],
        colors:[],
      },
      series: [],
    },
    thucVat: {
      options: {
        colors:[],
        labels: [],
      },
      series: [],
    },
    dongVat: {
      options: {
        colors:[],
        labels: [],
      },
      series: [],
    },
    diaBan: {
      options: {
        colors:[],
        labels: [],
      },
      series: [],
    },
    heSinhThai: {
      // options: {
        // labels: [],
      // },
      xaxis: {
        categories: [],
        labels: {
          style: {
            // colors: colors,
            fontSize: '12px'
          }
        }
      },
      series: [{
        name: "Số lương",
        data:[],
      }],
      chart: {
        // height: 600,
        // width: 800,
        type: 'bar',
        toolbar: {
        show: true,
        offsetX: 0,
        offsetY: 0,
        tools: {
          download: true,
          selection: true,
          zoom: true,
          zoomin: true,
          zoomout: true,
          pan: true,
          reset: true | '<img src="/static/icons/reset.png" width="20">',
          customIcons: [],
        },
        export: {
          csv: {
            filename: undefined,
            columnDelimiter: ",",
            headerCategory: "category",
            headerValue: "value",
            dateFormatter(timestamp) {
              return new Date(timestamp).toDateString();
            },
          },
          svg: {
            filename: undefined,
          },
          png: {
            filename: undefined,
          },
        },
        autoSelected: "zoom",
      },
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        show: false
      },
      colors: [],
      plotOptions: {
        bar: {
          columnWidth: '45%',
          distributed: true,
        }
      },
      // series: [],
    },
    triThucTruyenThong: {
      options: {
        colors:[],
        labels: [],
      },
      series: [],
    },
    noiLuuGius: {
      options: {
        colors:[],
        labels: [],
      },
      series: [],
    }
  }),
  created() {
    this.chart = {
      toolbar: {
        show: true,
        offsetX: 0,
        offsetY: 0,
        tools: {
          download: true,
          selection: true,
          zoom: true,
          zoomin: true,
          zoomout: true,
          pan: true,
          reset: true | '<img src="/static/icons/reset.png" width="20">',
          customIcons: [],
        },
        export: {
          csv: {
            filename: undefined,
            columnDelimiter: ",",
            headerCategory: "category",
            headerValue: "value",
            dateFormatter(timestamp) {
              return new Date(timestamp).toDateString();
            },
          },
          svg: {
            filename: undefined,
          },
          png: {
            filename: undefined,
          },
        },
        autoSelected: "zoom",
      },
    }
    this.kingdomData.options.chart = this.chart;
    this.doiTuong.options.chart = this.chart;
    this.diaBan.options.chart = this.chart;
    // this.heSinhThai.options.chart = this.chart;
    this.thucVat.options.chart = this.chart;
    this.dongVat.options.chart = this.chart;
    this.triThucTruyenThong.options.chart = this.chart;
    this.noiLuuGius.options.chart = this.chart
    this.bieuDoGioi();
    this.bieuDoDoiTuong();
    this.bieuDoThucVat();
    this.bieuDoDongVat();
    this.bieuDoDiaBan();
    this.bieuDoHeSinhThai();
    this.bieuDoTriThucTruyenThong();
    this.bieuDoNguonGenNoiLuuGiu()
  },
  methods: {
    bieuDoGioi() {
      let kingdoms = this.dulieubieudo.gioi;
      let label = kingdoms.map((el) => el.name_vietnamese);
      let series = kingdoms.map((el) => el.count);
      this.kingdomData.options.labels = label;
      this.kingdomData.series = series;
      kingdoms.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.kingdomData.options.colors.push(bgColor);
      });
    },
    bieuDoDoiTuong() {
      let data = this.dulieubieudo.doi_tuong;
      let label = data.map((el) => el.name);
      let series = data.map((el) => el.count);
      this.doiTuong.options.labels = label;
      this.doiTuong.series = series;
      data.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.doiTuong.options.colors.push(bgColor);
      });
    },
    bieuDoThucVat() {
      let data = this.dulieubieudo.thuc_vat;
      let label = data.map((el) => el.name_vietnamese);
      let series = data.map((el) => el.count);
      this.thucVat.options.labels = label;
      this.thucVat.series = series;
      data.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.thucVat.options.colors.push(bgColor);
      });
    },
    bieuDoDongVat() {
      let data = this.dulieubieudo.dong_vat;
      let label = data.map((el) => el.name_vietnamese ? el.name_vietnamese : el.name);
      let series = data.map((el) => el.count);
      this.dongVat.options.labels = label;
      this.dongVat.series = series;
      data.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.dongVat.options.colors.push(bgColor);
      });
    },
    bieuDoDiaBan() {
      let data = this.dulieubieudo.quan_huyen;
      let label = data.map((el) => el.name_vietnamese);
      let series = data.map((el) => el.count);
      this.diaBan.options.labels = label;
      this.diaBan.series = series;
      data.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.diaBan.options.colors.push(bgColor);
      });
    },
    bieuDoHeSinhThai() {
      let data = this.dulieubieudo.he_sinh_thai;
      let label = data.map((el) => el.name);
      let series = data.map((el) => el.count);
      // this.heSinhThai.options.labels = label;
      // this.heSinhThai.series = series; 
      this.heSinhThai.xaxis.categories = label;
      this.heSinhThai.series = [{
        name:"Số lượng",
        data:series,
      }];
      data.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.heSinhThai.colors.push(bgColor);
      });
      // console.log(this.heSinhThai.colors)
    },
    bieuDoTriThucTruyenThong() {
      let data = this.dulieubieudo.tri_thuc_truyen_thong;
      let label = data.map((el) => el.name);
      let series = data.map((el) => el.count);
      this.triThucTruyenThong.options.labels = label;
      this.triThucTruyenThong.series = series;
      data.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.triThucTruyenThong.options.colors.push(bgColor);
      });
    },
    bieuDoNguonGenNoiLuuGiu() {
      let data = this.dulieubieudo.noi_luu_giu;
      let label = data.map((el) => el.name);
      let series = data.map((el) => el.count);
      data.forEach(element => {
        var x = Math.floor(Math.random() * 256);
        var y = Math.floor(Math.random() * 256);
        var z = Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        this.noiLuuGius.options.colors.push(bgColor);
      });
      this.noiLuuGius.options.labels = label;
      this.noiLuuGius.series = series;
    },
  },
};
</script>

<style scoped>
.tenbieudo {
  font-size: 16px;
  padding-top: 20px;
  font-weight: bold;
}

.d-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 80px;
}

/* .item-chart {} */
/* .item-chart {
} */
</style>