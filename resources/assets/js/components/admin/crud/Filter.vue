<template>
  <div class="left-aside">
    <div class="scrollable">
      <div class="d-flex">
        <div style="fonts-size: 16px; font-weight: bold; flex: 1">Bộ lọc</div>
        <button
          type="button"
          class="btn-sm btn-info btn-rounded"
          v-if="showXoaBoLoc()"
          @click="deleteSearchFilter()"
        >
          <span>Xóa bộ lọc</span>
        </button>
      </div>
      <hr />
      <ul>
        <div
          @click="isStatus = -isStatus"
          data-toggle="collapse"
          data-target=".filter4"
          style="cursor: pointer; font-weight: normal"
        >
          Nguồn kinh phí
          <span
            :class="isStatus == 1 ? 'fa fa-angle-right' : 'fa fa-angle-down'"
            style="float: right"
          ></span>
        </div>
        <br />
        <li
          class="collapse filter4"
          style="margin-bottom: 10px"
          v-for="item in dataFilter.nguon_kinh_phi"
          :key="item.id"
        >
          <a
            @click="filterTable(item.id)"
            style="cursor: pointer"
            :style="
              filter.nguon_kinh_phi_id == item.id
                ? 'color: #408c5b; font-weight: bold'
                : 'color: black'
            "
          >
            {{ item.name }}
            <span style="float: right">{{ item.so_luong }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>