import ai_email from "./aiwriter/ai_email.js";

const request = new ajaxRequest();
const cm_storage = new Vuex.Store({
    state: {
        identifier: "",
        selected_smtps: [],
        selected_lists: [],
        custom_emails: "",
        do_spin: true,
        group_size: 20,
        delay_between: 30,
        email_subject: "",
        email_body: "",
        email_uns: "",
        ai: {
            type: "headline",
            data: null
        },
        sent_stat: { total: 0, sent: 0, init: false, pending: 0, started: false },
    },
    getters: {
        getState: function (state) {
            return state;
        },
    },
    mutations: {
        setHistory: function (state, arg) {
            state[arg.key] = arg.value;
        },
        updateStorage: function (state, arg) {
            try {
                if (Array.isArray(state[arg.key])) {
                    if (arg.keep !== undefined && arg.keep === false) {
                        let is_present = state[arg.key].indexOf(arg.val);
                        if (is_present > -1) {
                            state[arg.key].splice(is_present, 1);
                        }
                    }
                    else if (state[arg.key].indexOf(arg.val) < 0) {
                        state[arg.key].push(arg.val);
                    }
                }
                else {
                    state[arg.key] = arg.val;
                }
            } catch (err) { console.log(err); }
        }
    },
});

const compose_mail_app = new Vue({
    components: {
        'aiwriter': ai_email
    },
    el: "#compose_mail",
    data: {
        err: "",
        ai_init: false,
    },
    mounted: function () {
        document.addEventListener('DOMContentLoaded', () => {
        });
    },
    computed: {
        state: function () { return cm_storage.getters.getState; },
        mailSendButton: function () {
            const stat = this.state.sent_stat;
            return (stat.init && stat.pending !== false) ? `<i class="fas fa-spinner fa-spin"></i>&nbsp;${this.t("Sending...")}` : `${t("Send Mail")}`;
        },
    },
    methods: {
        t: function (txt, arr = []) {
            return t(txt, arr);
        },
        updateStorage: function (key, val, keep = true) {
            cm_storage.commit('updateStorage', { key: key, val: val, keep: keep });
        },
        doInput: function (e, store_key, detect = "value") {
            let data = e.target[detect];
            if (e.target.type == "checkbox") {
                this.updateStorage(store_key, data, e.target.checked);
            }
            else {
                this.updateStorage(store_key, data);
            }
        },
        composeMail: function (e) {

            let sent_stat_ob = { total: 0, sent: 0, init: false, pending: 0, started: false };
            sent_stat_ob.force = Math.random();
            this.updateStorage('sent_data', { ...sent_stat_ob });

            this.err = "";
            let seq_title = this.state.identifier.trim();
            let smtps = this.state.selected_smtps.join(',');
            let lists = this.state.selected_lists.join(',');
            let custom_emails = this.state.custom_emails.trim().split("\n").join(',');
            let group_size = parseInt(this.state.group_size);
            let delay_between = parseInt(this.state.delay_between) * 1000;
            let email_subject = this.state.email_subject.trim();
            let email_body = tinyMCE.get("email_content_composemail").getContent().trim();
            let email_uns = this.state.email_uns.trim();
            let seq_token = 0;
            if (this.state.selected_smtps.length < 1 || email_body.length < 1) {
                this.err = "<span style='color:#cc0052'>" + this.t("Make sure you selected SMTP and provided mailing content properly") + "</span>";
                return 0;
            }
            if (isNaN(delay_between) || isNaN(group_size) || group_size < 1) {
                this.err = "<span style='color:#cc0052'>" + this.t("Please provide numeric value for Group size and Delay. The group size should be one or more.") + "</span>";
                return 0;
            }

            let group_delay_and_extramailstostore = JSON.stringify({
                group: group_size,
                delay: delay_between,
                extra_mails: custom_emails,
            });

            let sent_data = email_subject + "@clickbrk@" + email_body + "@clickbrk@" + email_uns;
            const fileInput = $("#attachdata").val();
            const filename = fileInput.split("/").pop(); // Split the URL by '/' and get the last part
            $(".attachdata")
                .attr("href", fileInput)
                .text(filename)
                .show();

            e.target.disabled = true;

            request.postRequestCb("req.php", {
                compose_cf_mail: 1,
                type: "init",
                title: seq_title,
                smtps: smtps,
                lists: lists,
                custom_emails: custom_emails,
                sentdata: sent_data,
                extra_setup: group_delay_and_extramailstostore,
                formData: fileInput
            }, async (data) => {
                data = data.trim();
                if (data == '0') {
                    e.target.disabled = false;
                    this.err = "<span style='color:#cc0052;'>" + this.t("Unable to initiate the process, no mail sent.") + "</span>";
                }
                else {
                    try {
                        this.err = "<span style='color:green'>" + this.t("Initiating the process, please wait...") + "</span>";
                        data = JSON.parse(data);
                        seq_token = data.token;
                        let chunk = data.data;
                        console.log(chunk);

                        if (Array.isArray(chunk) && chunk.length > 0) {
                            let total_group = Math.ceil(chunk.length / group_size);
                            console.log(total_group);
                            let time_tosend = 0;

                            sent_stat_ob.init = true;
                            sent_stat_ob.total = chunk.length;
                            sent_stat_ob.pending = total_group;
                            sent_stat_ob.force = Math.random();
                            this.updateStorage("sent_stat", { ...sent_stat_ob });
                            for (let i = 0; i < total_group; i++) {
                                let temp_chunk = chunk.slice((i * group_size), (i * group_size + group_size));

                                await new Promise((resolve, reject) => {
                                    setTimeout((temp_chunk) => {

                                        request.postRequestCb('req.php', { compose_cf_mail: 1, type: 'compose', compose_data: JSON.stringify(temp_chunk), compose_token: seq_token }, (data) => {
                                            resolve(1);
                                            sent_stat_ob.started = true;
                                            console.log(data);
                                            this.err = "";
                                            data = data.trim();
                                            --sent_stat_ob.pending;
                                            if (sent_stat_ob.pending < 1) {
                                                e.target.disabled = false;
                                                sent_stat_ob.pending = false;
                                            }
                                            if (!isNaN(data)) {
                                                data = parseInt(data);
                                                sent_stat_ob.sent += data;
                                            }
                                            console.log(JSON.stringify(sent_stat_ob));
                                            sent_stat_ob.force = Math.random();
                                            this.updateStorage("sent_stat", { ...sent_stat_ob });

                                        });

                                    }, time_tosend, temp_chunk);
                                });
                                time_tosend = time_tosend + delay_between;
                            }
                        }
                        else {
                            this.err = "<span style='color:#cc0052;'>" + this.t("No subscriber found to send mail") + "</span>";
                        }
                    }
                    catch (err) {
                        e.target.disabled = false;
                        this.err = `<span style='color:#cc0052;'>${this.t(err.message)}</span>`;
                        console.log(err)
                    }
                }
            });
        },
        selectAllLists: function (e) {
            if (e.target.checked) {
                document.querySelectorAll("#select_lists_qfnl input[type=checkbox]").forEach((doc) => {
                    if (doc.value != e.target.value) {
                        if (!doc.checked) { doc.click(); }
                        doc.onclick = function () { if (e.target.checked) { e.target.checked = false; } }
                    }
                });
            }
        },
        openAIWriter(type) {
            this.state.ai.type = type;
            if (this.state.ai.type == "subject" || this.state.ai.type == "headline" || this.state.ai.type == "direct") {
                let email_subject = this.state.email_subject.trim();
                if (email_subject.length === 0) {
                    Swal.fire({
                        text: 'Please enter the email subject',
                        icon: 'error',
                        confirmButtonColor: "#3085d6",
                    });
                    return;
                }
                this.state.ai.type = "direct";
                this.state.ai.data = this.state.email_subject;
            }
            this.ai_init = true;
        },

        commitData(event) {
            let type = this.state.ai.type;
            if (type == "body" && event) {
                tinyMCE.get("email_content_composemail").setContent(event);
            } else if (event && (type == "subject" || type == "headline")) {
                this.state.email_subject = event;
            }
            this.ai_init = false;
        }
    }
});