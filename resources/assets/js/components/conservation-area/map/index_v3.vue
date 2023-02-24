<template>
  <div class="full-height site-container map-pdf">
    <tabs @clicked="tabClicked" @changed="tabChanged">
      <tab name="Bản đồ">
        <img :src="BanDoImg" />
      </tab>
      <tab name="Số liệu">
        <div style="width: 100%; display:flex; justify-content: center;" class="mb-2">
          <h1>Dữ liệu điều tra khảo sát năm 2022</h1>
        </div>
        <div>
          <table id="customers">
            <thead>
              <tr>
                <th style="width: 50px">
                  <div align="center">
                    STT
                  </div>
                </th>
                <th style="width: 30%">
                  <div align="center">
                    Hệ sinh thái
                  </div>
                </th>
                <th>
                  <div align="center">Diện tích (ha)</div>
                </th>
                <th>
                  <div align="center">Tỷ lệ (%)</div>
                </th>
                <th>
                  <div align="center">Ngành</div>
                </th>
                <th>
                  <div align="center">Lớp</div>
                </th>
                <th>
                  <div align="center">Bộ</div>
                </th>
                <th>
                  <div align="center">Họ</div>
                </th>
                <th>
                  <div align="center">Chi</div>
                </th>
                <th>
                  <div align="center">Loài</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width:50px">
                  <h4>I</h4>
                </td>
                <td style="width: 30%">
                  <h4>Hệ sinh thái tự nhiên</h4>
                </td>
                <td>
                  <h4>40,920.42</h4>
                </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
              </tr>
              <tr v-for="(item) in heSinhThaiTuNhien" :key="stt" class="isDifferent">
                <td class="isDifferent">{{ item.stt }}</td>
                <td style="width: 30%; flex-wrap:wrap" class="isDifferent">{{ item.heSinhThai }}</td>
                <td class="isDifferent">{{ item.dienTich }}</td>
                <td class="isDifferent">{{ item.tyLe }}</td>
                <td class="isDifferent">{{ item.nganh }}</td>
                <td class="isDifferent">{{ item.lop }}</td>
                <td class="isDifferent">{{ item.bo }}</td>
                <td class="isDifferent">{{ item.ho }}</td>
                <td class="isDifferent">{{ item.chi }}</td>
                <td class="isDifferent">{{ item.loai }}</td>
              </tr>
              <tr>
                <td style="width:50px">
                  <h4>II</h4>
                </td>
                <td style="width: 30%">
                  <h4>Hệ sinh thái nhân tạo</h4>
                </td>
                <td>
                  <h4>295,251.31</h4>
                </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
              </tr>
              <tr v-for="(item) in heSinhThaiNhanTao" :key="stt" class="isDifferent">
                <td class="isDifferent">{{ item.stt }}</td>
                <td style="width: 30%; flex-wrap:wrap" class="isDifferent">{{ item.heSinhThai }}</td>
                <td class="isDifferent">{{ item.dienTich }}</td>
                <td class="isDifferent">{{ item.tyLe }}</td>
                <td class="isDifferent">{{ item.nganh }}</td>
                <td class="isDifferent">{{ item.lop }}</td>
                <td class="isDifferent">{{ item.bo }}</td>
                <td class="isDifferent">{{ item.ho }}</td>
                <td class="isDifferent">{{ item.chi }}</td>
                <td class="isDifferent">{{ item.loai }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </tab>
    </tabs>
  </div>
</template>
<script>
import BanDoImg from './HST_HaNoi.jpg'
export default {
  props: ["quan_huyen"],
  data() {
    return {
      BanDoImg,
      heSinhThaiTuNhien: [
        {
          stt: 1,
          heSinhThai: 'HST rừng kín thường xanh mưa ẩm á nhiệt đới chủ yếu cây lá rộng trên núi đất (độ cao trên 600m)',
          dienTich: '1,003.58',
          tyLe: '0.30',
          nganh: '7',
          lop: '20',
          bo: '107',
          ho: '341',
          chi: '1,350',
          loai: '2,715',
        },
        {
          stt: 2,
          heSinhThai: 'HST rừng kín thường xanh mưa ẩm nhiệt đới cây lá rộng trên núi đất (độ cao <600m)',
          dienTich: '2,102.60',
          tyLe: '0.63',
          nganh: '7',
          lop: '20',
          bo: '108',
          ho: '356',
          chi: '1,437',
          loai: '3,024',
        },
        {
          stt: 3,
          heSinhThai: 'HST rừng hỗn giao tre nứa xen cây gỗ',
          dienTich: '413.93',
          tyLe: '0.12',
          nganh: '6',
          lop: '14',
          bo: '88',
          ho: '269',
          chi: '904',
          loai: '1,511',
        },
        {
          stt: 4,
          heSinhThai: 'HST rừng trên núi đá vôi',
          dienTich: '4,347.19',
          tyLe: '1.29',
          nganh: '6',
          lop: '14',
          bo: '88',
          ho: '263',
          chi: '845',
          loai: '1,392',
        },
        {
          stt: 5,
          heSinhThai: 'HST tràng cỏ, cây bụi',
          dienTich: '15,879.53',
          tyLe: '4.72',
          nganh: '5',
          lop: '13',
          bo: '82',
          ho: '237',
          chi: '775',
          loai: '1283',
        },
        {
          stt: 4,
          heSinhThai: 'HST đất ngập nước',
          dienTich: '17,173.53',
          tyLe: '5.11',
          nganh: '15',
          lop: '31',
          bo: '119',
          ho: '330',
          chi: '1,006',
          loai: '1,578',
        },
        
      ],
      heSinhThaiNhanTao:[
        {
          stt: 7,
          heSinhThai: 'HST rừng trồng',
          dienTich: '12,034.98',
          tyLe: '3.58',
          nganh:'6',
          lop:'14',
          bo:'82',
          ho:'243',
          chi:'760',
          loai:'1,268',
        },
        {
          stt: 8,
          heSinhThai: 'HST nông nghiệp',
          dienTich: '153,347.52',
          tyLe: '45.62',
          nganh:'8',
          lop:'23',
          bo:'110',
          ho:'330',
          chi:'1,015',
          loai:'1,670',
        },
        {
          stt: 9,
          heSinhThai: 'HST khu dân cư đô thị',
          dienTich: '86,767.84',
          tyLe: '25.81',
          nganh:'16',
          lop:'36',
          bo:'134',
          ho:'432',
          chi:'1,483',
          loai:'2,476',
        },
        {
          stt: 10,
          heSinhThai: 'HST khu dân cư nông thôn',
          dienTich: '43,100.97',
          tyLe: '12.82',
          nganh:'16',
          lop:'34',
          bo:'129',
          ho:'401',
          chi:'1,394',
          loai:'2,269',
        },
        
      ],
    }
  },

  methods: {
    tabClicked() { },
    tabChanged() { },
  },
  watch: {},
};
</script>
<style>
.map-pdf .tabs-component {
  /* margin: 4em 0; */
}
.map-pdf .tabs-component-tabs {
  border: solid 1px #ddd;
  border-radius: 6px;
  margin-bottom: 5px;
}

@media (min-width: 700px) {
  .map-pdf .tabs-component-tabs {
    border: 0;
    align-items: stretch;
    display: flex;
    justify-content: flex-start;
  }
}

.map-pdf .tabs-component-tab {
  color: #999;
  font-size: 14px;
  font-weight: 600;
  margin-right: 0;
  list-style: none;
}

.map-pdf .tabs-component-tab:not(:last-child) {
  border-bottom: dotted 1px #ddd;
}

.tabs-component-tab:hover {
  color: #666;
}

.map-pdf .tabs-component-tab.is-active {
  color: #000;
}

.map-pdf .tabs-component-tab.is-disabled * {
  color: #cdcdcd;
  cursor: not-allowed !important;
}

@media (min-width: 700px) {
  .map-pdf .tabs-component-tab {
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 3px 3px 0 0;
    margin-right: 0.5em;
    transform: translateY(2px);
    transition: transform 0.3s ease;
  }

  .map-pdf .tabs-component-tab.is-active {
    border-bottom: solid 1px #fff;
    z-index: 2;
    transform: translateY(0);
  }
}

.map-pdf .tabs-component-tab-a {
  align-items: center;
  color: inherit;
  display: flex;
  padding: 0.75em 1em;
  text-decoration: none;
}

.map-pdf .tabs-component-panels {
  padding: 4em 0;
}

@media (min-width: 700px) {
  .map-pdf .tabs-component-panels {
    border-top-left-radius: 0;
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 0 6px 6px 6px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    padding: 2em 2em;
  }
}

#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td,
#customers th {
  border: 1px solid rgb(182, 181, 181);
  padding: 8px;
}

#customers tr:nth-child(even) {
  background-color: #f2f2f2;
}

#customers tr:hover {
  background-color: #ddd;
}
#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #f0f0f0;
  color: rgb(0, 0, 0);
  font-weight: 700 ;
  font-size: 18px;
}
</style>
  