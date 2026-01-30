export let container = `
<div class="row main_row">
    <div class="col-sm-12">
        <div class="card me-auto ms-auto" :style="{'border': use_mode === 'external' ? '0px !important' : '0px'}">
            <div class="card-body">
                <form method="post" action="#">
                    <div class="mb-3" v-if="response === null">
                        <label for="prompt_option" name="prompt_option" class="form-label fw-semibold">{{t("What do you want to do?")}}</label>
                        <select class="form-control form-select" id="prompt_option" v-model="prompt_option">
                            <option value="headline">{{t("Write Headline")}}</option>
                            <option value="description">{{t("Write Description")}}</option>
                            <option value="list">{{t("Create List")}}</option>
                            <option value="features">{{t("Create Features")}}</option>
                            <option value="image">{{t("Create Image")}}</option>
                            <option value="body">{{t("Email Body")}}</option>
                        </select>
                    </div>
                    <div class="mb-3" v-for="(field, index) in fields[prompt_option]" :key="index" v-if="response === null">
                        <label :for="prompt_option+'_'+index" class="form-label fw-semibold">{{t(field.label)}}</label>
                        <textarea rows="7" :placeholder="t(field.placeholder)" type="text" class="form-control" :id="prompt_option+'_'+index" :name="prompt_option+'_'+index" v-model="field.value" v-if="field.type=='textarea'"></textarea>
                        <input type="text" :placeholder="t(field.placeholder)" class="form-control" :id="prompt_option+'_'+index" :name="prompt_option+'_'+index" v-model="field.value" v-if="field.type=='text'" />
                        <select class="form-control form-select" :id="prompt_option+'_'+index" v-model="field.value" :name="prompt_option+'_'+index" v-if="field.type=='select'">
                            <option v-for="(option, i) in field.options" :key="i" :value="option.value">{{t(option.text)}}</option>
                        </select>

                        <!-- For the radio button -->
                        <div class="d-flex flex-wrap" v-if="field.type=='radio'">
                            <label class="btn btn-outline btn-active-light-primary d-flex justify-content-center flex-stack text-start mx-2" :class="{ 'active text-primary' : field.value==style.value }" v-for="(style, i) in field.style" :key="i">
                                <div class="d-flex align-items-center" style="white-space: nowrap;">
                                    <input class="form-check-input d-none" type="radio" :name="field.name" v-model="field.value" :value="style.value" />
                                    <div class="fw-semibold">
                                        <i class="me-2" :class="style.class"></i>{{t(style.text)}}
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <p class="text-danger text-center" v-if="response === null && error !== null">{{t(error)}}</p>

                    <!-- Output of the desired response -->
                    <div class="output" v-if="response !== null">
                        <div class="mb-3" v-if="prompt_option=='image'">
                            <img :src="response" style="max-height:350px; max-width:350px;" class="rounded mx-auto d-block mb-3" />
                            <a :href="response" target="_blank" class="btn btn-primary" download="ai_image.png"><i class="fas fa-download"></i>&nbsp; {{t(Download)}}</a>
                        </div>
                        <div class="input-group mb-3" v-for="(res, key) in response" :key="key" v-else-if="prompt_option=='headline'">
                            <input type="text" class="form-control border-primary" v-model="response[key]" />
                            <button type="button" class="btn btn-primary" @click="copy(response[key])"><i class="fas fa-copy"></i>&nbsp; {{t('Copy')}}</button>
                        </div>
                        <div class="mb-3" v-else>
                            <textarea rows="7" class="form-control mb-3" :aria-label="response">{{response}}</textarea>
                            <button type="button" target="_blank" class="btn btn-primary" @click="copy(response)"><i class="fas fa-copy"></i>&nbsp; {{t("Copy")}}</button>
                        </div>
                        <button type="button" class="btn btn-primary" @click="previous()"><i class="fas fa-arrow-left"></i>&nbsp; {{t("Previous")}}</button>
                    </div>

                    <button type="button" class="btn btn-primary" :disabled="form_submitting" v-if="response === null" @click="generate()">
                        <span class="indicator-label" v-if="!form_submitting"><i class="far fa-edit"></i>&nbsp; {{t("Generate")}}</span>
                        <span class="indicator-progress" v-else>{{t("Please wait...")}}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
`;

export let external_container = `<div class="container-fluid external-media-loader overlay" style="position:absolute;">
<div class="row d-flex justify-content-center">
    <div class="col-sm-10" style="max-width: 50%;">
        <div class="card pnl">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-10"><img src="assets/img/aiwriter.png" />${t('AI Writer')}</div>
                    <div class="col-sm-2 text-end">
                    <i class="fas fa-times-circle" v-if="editor" onclick="handleAI(true)" style="cursor:pointer"></i>
                    <i class="fas fa-times-circle" v-else="editor" @click="emitData()" style="cursor:pointer"></i>
                    </div>
                </div>
            </div>
            <div class="external-content-body my-3 overflow-hidden">
                ${container}
            </div>
        </div>
    </div>
</div>
</div>`;