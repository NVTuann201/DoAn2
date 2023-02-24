<template>
  <div class="anchor-block--tabs">
    <section class="horizontal-stripe light-background seperator--b">
      <div class="container--desktop">
        <div class="row">
          <div class="col-xs-12">
            <tabs>
              <tab v-for="g in result" :name="g.group" :key="g.id">
                <table class="table table--compact">
                  <colgroup>
                    <col span="1" style="width: 50%" />
                    <col span="1" style="width: 50%" />
                  </colgroup>
                  <tbody>
                    <tr v-for="(item, idx) in g.children" :key="idx">
                      <td dir="auto">{{ item.label }}</td>
                      <td dir="auto">
                        <template v-if="item.type === 'boolean'">
                          <span v-if="data[item.field] === 1">Có</span>
                          <span v-if="data[item.field] === 0">Không</span>
                          <span v-if="data[item.field] === null"
                            >Không xác định</span
                          >
                        </template>
                        <span
                          v-if="
                            item.type === 'text' ||
                            item.type === 'number' ||
                            item.type === 'date'
                          "
                        >
                          {{ data[item.field] }}
                        </span>
                        <span v-if="item.type === 'file'">
                          <a
                            :href="f.disk"
                            v-for="f in data[item.field]"
                            :key="f.id"
                            >{{ f.name }}</a
                          >
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </tab>
            </tabs>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import { result } from "./coso";
export default {
  props: ["data"],
  data() {
    return {
      result,
    };
  },
};
</script>
<style >
.tabs-component {
  /* margin: 4em 0; */
}
.tabs-component-tabs {
  border: solid 1px #ddd;
  border-radius: 6px;
  margin-bottom: 5px;
  padding-left: 0;
}

@media (min-width: 700px) {
  .tabs-component-tabs {
    border: 0;
    align-items: stretch;
    display: flex;
    justify-content: flex-start;
    margin-bottom: -1px;
  }
}

.tabs-component-tab {
  color: #999;
  font-size: 14px;
  font-weight: 600;
  margin-right: 0;
  list-style: none;
}

.tabs-component-tab:not(:last-child) {
  border-bottom: dotted 1px #ddd;
}
.tabs-component-tab:last-child {
  margin-right: 0;
}

.tabs-component-tab:hover {
  color: #666;
}

.tabs-component-tab.is-active {
  color: #000;
}

.tabs-component-tab.is-disabled * {
  color: #cdcdcd;
  cursor: not-allowed !important;
}

@media (min-width: 700px) {
  .tabs-component-tab {
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 3px 3px 0 0;
    margin-right: 0.5em;
    transform: translateY(2px);
    transition: transform 0.3s ease;
  }

  .tabs-component-tab.is-active {
    border-bottom: solid 1px #fff;
    z-index: 2;
    transform: translateY(0);
  }
}

.tabs-component-tab-a {
  align-items: center;
  color: inherit;
  display: flex;
  padding: 0.75em 1em;
  text-decoration: none;
}

.tabs-component-panels {
  padding: 4em 0;
}

@media (min-width: 700px) {
  .tabs-component-panels {
    border-top-left-radius: 0;
    background-color: #fff;
    border: solid 1px #ddd;
    border-radius: 0 6px 6px 6px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    padding: 2em 2em;
  }
}
</style>