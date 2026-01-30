import ai_email from "./aiwriter/ai_email.js";
new Vue({
    components: {
        'aiwriter': ai_email
    },

    el: "#aiwriterEditor",
    data: {
        init: false,
    },
    methods: {
        close() { this.init = false }
    }
});