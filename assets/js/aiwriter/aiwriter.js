import ai_email from "./ai_email.js";
new Vue({
    el: "#aiwriter",
    components: {
        'aiwriter': ai_email
    },
    data() {
        return {
            ai_init: true,
            data: null
        }
    },

    methods: {
        commitData(event) {
            console.log(event);
        }
    },
});