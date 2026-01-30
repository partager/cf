import { external_container, container } from './ai_containers.js';
var ai_email = {
    template: `<div v-if="use_mode=='external'" class="external-container-media">${external_container}</div>
    <div class="card pnl mx-auto" style="max-width:50%;" v-else>
        <div class="card-header">
            <div class="row">
                <div class="col-sm-10"><img src="assets/img/Ai-W.png" />&nbsp; ${t("AI Writer")}</div>
            </div>
        </div>
        <div class="external-content-body my-3 overflow-hidden">
            ${container}
        </div>
    </div>`,

    data() {
        return {
            request: new ajaxRequest(),
            prompt_option: "headline",
            fields: {
                headline: [
                    {
                        label: "Enter headline",
                        type: "text",
                        placeholder: "Enter headline",
                        value: null,
                        error: "Enter headline"
                    },
                ],
                body: [
                    {
                        label: "What is your product's name?",
                        type: "text",
                        placeholder: "What is your product's name?",
                        value: null,
                        error: "What is your product's name?"
                    },
                    {
                        label: "Tell us about your email",
                        type: "textarea",
                        placeholder: "Tell us about your email",
                        sub: "Tell the AI what your Email is about. Please be as detailed as you want",
                        value: null,
                        error: "Tell us about your email"
                    },
                    {
                        label: "What is your business type?",
                        type: "text",
                        placeholder: "What is your business type?",
                        value: null,
                        error: "What is your business type?"
                    },
                    {
                        label: "Choose your Email Style",
                        type: "radio",
                        value: 'casual',
                        error: "Please select email style",
                        style: [
                            {
                                class: "me-2 fab fa-shirtsinbulk fs-2x",
                                text: "Casual",
                                value: "casual"
                            },
                            {
                                class: "me-2 fa fa-smile fs-2x",
                                text: "Friendly",
                                value: "friendly"
                            },
                            {
                                class: "me-2 fab fa-black-tie fs-2x",
                                text: "Formal",
                                value: "formal"
                            },
                            {
                                class: "me-2 fa fa-tshirt fs-2x",
                                text: "Informal",
                                value: "informal"
                            },
                            {
                                class: "me-2 fa fa-grin-tongue-wink fs-2x",
                                text: "Funny",
                                value: "funny"
                            },
                            {
                                class: "me-2 fa fa-lightbulb fs-2x",
                                text: "Inspirational",
                                value: "inspirational"
                            },
                            {
                                class: "me-2 fa fa-info-circle fs-2x",
                                text: "Informative",
                                value: "informative"
                            },
                        ]
                    },
                ],
                description: [
                    {
                        label: "Give us some details or input",
                        type: "textarea",
                        placeholder: "Give us some details or input",
                        value: null,
                        error: "Give us some details or input"
                    }
                ],
                list: [
                    {
                        label: "Give us some details or input",
                        type: "textarea",
                        placeholder: "Give us some details or input",
                        value: null,
                        error: "Give us some details or input"
                    }
                ],
                features: [
                    {
                        label: "Give us some details or input",
                        type: "textarea",
                        placeholder: "Give us some details or input",
                        value: null,
                        error: "Give us some details or input"
                    }
                ],
                image: [
                    {
                        label: "Give us some details or input",
                        type: "textarea",
                        placeholder: "Give us some details or input",
                        value: null,
                        error: "Give us some details or input"
                    },
                    {
                        label: "Select image type",
                        type: "select",
                        value: "photo",
                        error: "Select image type",
                        options: [
                            {
                                text: "Cartoon",
                                value: "cartoon"
                            },
                            {
                                text: "Illustration",
                                value: "illustration"
                            },
                            {
                                text: "3D Rendering",
                                value: "rendering3d"
                            },
                            {
                                text: "Photo",
                                value: "photo"
                            },
                            {
                                text: "Painting",
                                value: "painting"
                            },
                        ]
                    }
                ],
            },
            loading: true,
            response: null,
            form_submitting: false,
            error: null,
        }
    },

    props: {
        use_mode: {
            type: String,
            default: "external"
        },
        type: {
            type: String,
            default: null
        },
        data: {
            type: String,
            default: null
        },
        editor: {
            type: Boolean,
            default: false
        }
    },

    mounted() {
        if (this.type == "direct") {
            this.prompt_option = "headline";
            this.fields.headline[0].value = this.data;
            this.generate();
        } else if (this.type == "body") {
            this.prompt_option = "body";
        }
    },

    methods: {
        t: function (txt, arr = []) {
            return t(txt, arr);
        },
        generate() {
            this.error = null;

            // Check if error exists
            if (this.validate()) {
                return;
            }

            var detailed_explanation_var = "";
            var all_options = this.fields[this.prompt_option];

            switch (this.prompt_option) {
                case 'headline':
                    detailed_explanation_var = `Please suggest 5-10 alternative headlines for the following headline. Do not put anything in your reply except the alternative headlines. No “Okay”, no “Sure”. Just the headlines. Do not put numbers before the headlines. Do not put bullets or - either. Just put the headlines separated by a line-break.\nThe original headline is below:\n` + all_options[0].value;
                    break;

                case 'description':
                    detailed_explanation_var = `Create a product description based on the following product information. Do not put anything in your reply except the product description. No greetings, no thanks. Just the product description. Make the description catchy and appealing.\nThe product information is below.\n` + all_options[0].value;
                    break;

                case 'list':
                    detailed_explanation_var = `See the text below and summarize it into a list of points. Make a list without numbers. Make sure to include all the information in the list. Do not put anything in your reply except the list. No greetings, no thanks. Just the list of points.\nThe text is below.\n` + all_options[0].value;
                    break;

                case 'features':
                    detailed_explanation_var = `Create a list of features based on the product description below. Include all salient features of the product. Make sure the list of features is accurate as per the description provided.\nCreate a list of 5 to 15 features.\nMake the description catchy and appealing.\nDo not put anything in your reply except the list of features. No greetings, no thanks. Just the list of features.\nThe product description is below.\n` + all_options[0].value;
                    break;

                case 'image':
                    detailed_explanation_var = `Create an image in the ` + all_options[1].value + ` style based on the details given below.\n` + all_options[0].value;
                    break;

                case 'body':
                    detailed_explanation_var = `Hi ChatGpt! My product's name is '${all_options[0].value}' and my line of business is '${all_options[1].value}'.
                    Please write an email based on the information below for my business. Return only the email text, do not add any greetings of explanation. I want just the actual email.
                    The email should in ${all_options[3].value} style.
                    Base the contents of the email on the information below.
                    ${all_options[2].value}`;
                    break;

                default:
                    break;
            }

            const req_data = {
                manage_aiwriter: 1,
                prompt_option: this.prompt_option,
                detailed_explanation: detailed_explanation_var,
            };

            this.form_submitting = true;

            this.request_data(req_data);
        },

        request_data(data) {
            this.request.postRequestCb("req.php", data, (res) => {
                this.form_submitting = false;
                var res = JSON.parse(res);
                // Check if 'res' is not null
                if (res !== null) {
                    try {
                        if (this.prompt_option == "headline") {
                            const lines = res.message.split("\n");
                            const modifiedLines = lines.map(line => {
                                var cleanedLine = line.replace(/^[\d*-.\s]+/, '');
                                cleanedLine = cleanedLine.replace(/\n/g, '<br>');
                                cleanedLine = cleanedLine.replace("Alternative Headlines:", "");
                                cleanedLine = cleanedLine.replace("Alternative headlines:", "");
                                if (cleanedLine.length !== 0) {
                                    return cleanedLine.replace(/\n/g, '<br>');
                                }
                                return false;
                            }).filter(Boolean);
                            this.response = modifiedLines;
                        } else {
                            this.response = res.message;
                        }
                    } catch (error) {
                        this.error = 'Something went wrong.'

                    }
                } else {
                    // Handle the case when 'res' is null                    
                    this.error = 'Your license has been expired, please reactivate it.';
                }
            });
        },

        validate() {
            let forms = this.fields[this.prompt_option], error = 0;
            for (let form in forms) {
                if (forms[form].value === null || forms[form].value === "") {
                    this.error = forms[form].error;
                    error = 1;
                    break;
                }
            }
            return error;
        },

        previous() {
            this.form_submitting = false;
            this.response = null;
        },

        copy(str) {
            navigator.clipboard.writeText(str);
            this.setSwalMixin(t("Copied successfully"), "success");
        },

        setSwalMixin(message, iconName, timeValue = 2000) {
            var toastMixin = Swal.mixin({
                toast: true,
                icon: iconName,
                title: 'Message',
                position: 'top-right',
                showConfirmButton: false,
                timer: timeValue,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            toastMixin.fire({
                title: message
            });
        },
        emitData() {
            this.$emit('close', true);
            this.$emit('body', this.response);
        }

    },

    emits: ["close", "body"]
};

export default ai_email;