
  Vue.createApp({
    data() {
      return {
        fullname:'',
      }
    },
    methods: {
    get_fullname() {
        this.fullname = 'dddd';
    }
  },
  }).mount('#nav')