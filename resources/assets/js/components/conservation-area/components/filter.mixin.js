export default {
  data() {
    return {
      page: 1,
      pagesCount: 1,
      loading: false,
      filtersCount: 0,
      resultCount: 0,
      data: [],
      filter: {
        search: "",
      },
      tab: 0,
      notInitial: false,
      year: [],
      type: [],
      category: [],
      isSearching: false,
    };
  },
  computed: {
    categoryFilters() {
      const filters = JSON.parse(JSON.stringify(this.category));
      filters.forEach((f) => (f.label = this.$t(f.label)));
      return filters;
    },
    typeFilter() {
      const filters = JSON.parse(JSON.stringify(this.type));
      filters.forEach((f) => (f.label = this.$t(f.label)));
      return filters;
    },
    yearFilter() {
      const filters = JSON.parse(JSON.stringify(this.year));
      filters.forEach((f) => (f.label = this.$t(f.label)));
      return filters;
    },
    isShowMap() {
      return this.tab === "map";
    },

    aFilter() {
      return this.categoryFilters.find((item) => item.type === "a");
    },
    liFilter() {
      return this.categoryFilters.filter((item) => item.type === "li");
    },
    pFilter() {
      return this.categoryFilters.filter((item) => item.type === "p");
    },
  },
  methods: {
    initFilters() {
      for (const f of this.categoryFilters) {
        this.$set(this.filter, f.key, null);
      }
      this.$set(this.filter, "type");

      for (const f of this.yearFilter) {
        this.$set(this.filter, f.key, ["", ""]);
      }

      for (const filter in this.filter) {
        if (filter === "search") continue;
        this.$watch(`filter.${filter}`, function (_, oldValue) {
          if (oldValue === null) return;
          this.updateFiltersCount();
          this.search(1);
        });
      }
    },
    updateFiltersCount() {
      let count = 0;
      for (const filter of Object.values(this.filter)) {
        if (filter instanceof Array) {
          if (filter.length === 0) continue;
          if (filter[0] instanceof Object || filter[0] || filter[1]) count++;
        }
      }
      this.filtersCount = count;
    },
    async search(page) {
      if (this.isSearching) return;
      this.isSearching = true;
      if (page) this.page = page;
      const params = Object.assign(
        {
          page: this.page,
          search: this.filter.search,
        },
        this.convertFilter(this.filter)
      );

      this.loading = true;
      try {
        const { data } = await axios.get(this.searchUrl, { params });
        this.data = data.data;
        this.pagesCount = data.last_page;
        this.resultCount = data.total;
        this.page = data.current_page;
        //this.notInitial = true;
      } catch (error) {
      } finally {
        this.loading = false;
        this.isSearching = false;
      }
    },
    convertFilter(filter) {
      let params = {};
      for (const f in filter) {
        if (filter[f] instanceof Array) {
          if (this.yearFilter.map((i) => i.key).includes(f))
            params[f] = filter[f];
          else params[f] = filter[f].map((i) => i.id);
        }
      }
      return params;
    },

    clickTab(val) {
      this.tab = val;
      if (val === "map")
        setTimeout(() => {
          this.$refs.map.map.resize();
        }, 0);
    },
    setCategoryFilter(data) {
      const { key, value } = data;
      if (!value) return;
      if (value.name_vietnamese) value.name = value.name_vietnamese;
      if (value.orig_name) value.name = value.orig_name;
      delete value.name_vietnamese;
      delete value.orig_name;
      value.show = false;
      this.filter[key] = [value];
      this.search(1);
    },
  },
  created() {
    this.initFilters();

    this.search();
  },
};
