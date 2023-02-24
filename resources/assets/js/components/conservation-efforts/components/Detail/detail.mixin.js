import flat from "flat";
export default {
  props: ["data"],
  data() {
    return {
      headerData: {},
    };
  },
  computed: {
    serializedData() {
      return flat(this.data, {
        safe: true,
      });
    },
    infoData() {
      const info = JSON.parse(JSON.stringify(this.info));
      info.forEach((f) => (f.label = this.$t(f.label)));
      return info;
    },
    otherData() {
      const other = JSON.parse(JSON.stringify(this.other));
      other.forEach((f) => (f.label = this.$t(f.label)));
      return other;
    },
  },
  methods: {},
  created() {
    this.headerData.tinh_thanh = this.data.tinh_thanh;
    this.setHeaderData();
  },
};
