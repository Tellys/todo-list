<template>
    <Vueform ref="form$" v-model="dataForm" :sync="true" addClass='vf-my-form' v-bind="builderObject" :endpoint="async function (data, form$) {
        const formData = data // data returned by `formData` option - by default it's a `FormData` instance
        submitForm(form$.data)
        //console.log(formData, form$.data);
        //submit form data
    }">
    </Vueform>
    <div class="vf-layout-inner-wrapper vf-col-12 my-3">
        <button class="vf-btn vf-btn-secondary btnDynamicFormCancel" @click.prevent="fnRouteReturnSuccess()"
            title="Voltar" type="button">Voltar</button>
    </div>
</template>

<script>
import Api from '@/services/Api';
import MyAlert from '@/services/MyAlert';
import { Vueform, useVueform } from '@vueform/vueform'
import Store from "@/store";
import { mapState, mapGetters } from "vuex";
import Masks from '@/services/Masks';

export default {
    name: 'DynamicForm',

    mixins: [Vueform],
    setup: useVueform,

    data: () => ({
        thisSchema: {},
        thisSteps: {},
        builderObject: {},
        endPointBackEndSystem: `${process.env.VUE_APP_API_BACK_END_URL}`,
        dataForm: {},
        oldVMask: null,
    }),

    props: {
        myUri: String,
        myUriToSubmit: String,
        mySteps: Number,
        myFormMethod: String,
        routeNameSuccessRedirect: [String, Object],
    },

    computed: {
        ...mapState("dynamicForm", ["myProps", 'dynamicDataRegistration']),
        ...mapGetters('searchCpfCnpjApi', ['myCpfData', 'myCnpjData']),
    },

    mounted() {
        this.getItens();
    },
    watch: {
        dataForm(newValue) {
            //console.log('dataForm', newValue);
            return this.insertMask(newValue)
        },
        dynamicDataRegistration(newValue, oldValue) {
            console.log(`Updating from ${oldValue} to ${newValue}`);
            return this.$refs.form$.update(newValue)
        },
        myCnpjData(newValue, oldValue) {
            console.log('myCnpjData', oldValue, newValue);
            //return this.$refs.form$.update(newValue)
        },
    },

    methods: {
        testalert() {
            console.log('chamou a test onchange')
        },
        fnRouteReturnSuccess(r = null) {
            console.log('this.$route?.query',r?.redirect || this.$route?.query?.redirect, this.$route?.query);

            let myVar = this.routeNameSuccessRedirect;

            if (r?.redirect || this.$route?.query?.redirect) {
                myVar = null;
                return this.$router.push(r?.redirect ?? this.$route?.query?.redirect);
            }

            if (this.routeNameSuccessRedirect?.name) {
                myVar = { name: this.routeNameSuccessRedirect.name };
            }

            //queryPersonal is for composer url with components
            if (this.routeNameSuccessRedirect?.queryPersonal) {
                if (!myVar?.query) {
                    myVar.query = [];
                }

                for (const [key, value] of Object.entries(this.routeNameSuccessRedirect.queryPersonal)) {
                    myVar['query'][key] = r.data[value];
                }
                return this.$router.push(myVar);
            }

            if (this.routeNameSuccessRedirect?.query) {
                if (!myVar?.query) {
                    myVar.query = [];
                }
                //myVar['query'].push(this.routeNameSuccessRedirect?.query);
                myVar['query'] = this.routeNameSuccessRedirect.query;
                return this.$router.push(myVar);
            }

            if (myVar === 'refresh') {
               return location.reload();
            }

            if (!this.routeNameSuccessRedirect?.params && this.routeNameSuccessRedirect?.name) {
                myVar['params'] = { id: r.data.id };
            }

            return this.routeNameSuccessRedirect ? this.$router.push(myVar) : this.$router.back();
        },
        insertMask(data) {

            if (!this.builderObject.schema) {
                return false;
            }

            let mySchema = this.builderObject.schema;

            for (const [key, value] of Object.entries(data)) {
                if (value) {

                    //para não ficar aplicando mask em valores que estão com mask
                    if (this.oldVMask != value) {
                        let v = Masks.init(mySchema[key]['mask'], value.toString())
                        this.oldVMask = v
                        if (v) {
                            data[key] = v

                            //eu estava tentando fazer um esquema de placeholder dinamico
                            // this.$refs.form$.update(
                            //     { placeholder: `${v} new placeholder` }
                            // )  // = `${v} new placeholder`
                        }

                        //this.$refs.form$.el$(key).update(v);
                    }
                }
            }
            this.dataForm = data;
            return
        },

        updateData(data = false) {
            if (data) {
                return data;
            }

            return Store.state("dynamicForm/forms/registration")
        },

        async getItens() {

            Store.commit("dynamicForm/SET_MY_PROPS", this.$props);

            //console.log('/*/*/*', Store.getters['dynamicForm/myUriBase']);

            // if (!Store.getters['dynamicForm/myUriBase']) {
            //     MyAlert.alertError('O formulário não irá funcionar. É necessário definr a Uri do arquivo');
            // }

            let myUri = this.myUri;
            if (this.mySteps) {
                console.log(this.myUri + '?steps=' + this.mySteps);
                myUri = this.myUri + '?steps=' + this.mySteps;
            }
            //await Api.get(this.myUri + '/schema/' + this.mySteps).then((r) => {
            await Api.get(myUri).then(async (r) => {

                if (!r?.data?.schema) {
                    return false;
                }

                console.log('r.data.schema',r.data.schema);

                this.builderObject.schema = r.data.schema;

                if (this.builderObject.schema?.image) {
                    this.builderObject.schema.image.uploadTempEndpoint = async function (image, el$) {
                        let response;
                        let request = el$.form$.convertFormData({
                            image,
                        })

                        await Api.post(Store.getters['dynamicForm/myUriBase'] + '/upload-temporary', request, 'multipart/form-data')
                            .then((r) => {
                                response = r;
                                //return true;
                            }, (error) => {
                                MyAlert.alertError(Api.displayError(error));
                                return false;
                            })

                        return {
                            tmp: response.data.tmp.basename, // the temp file identifier
                            originalName: response.data.originalName, // the original name of the file that will be displayed to the user
                            data: response.data,
                        }
                    };

                    this.builderObject.schema.image.removeTempEndpoint = async function (image, el$) {

                        let request = el$.form$.convertFormData({
                            image,
                        })

                        await Api.post(Store.getters['dynamicForm/myUriBase'] + '/remove-temporary', request)
                            .then(() => {
                                return true
                            }, (error) => {
                                MyAlert.alertError(Api.displayError(error));
                                return false;
                            })
                    };

                    this.builderObject.schema.image.removeEndpoint = async function (image, el$) {

                        let request = el$.form$.convertFormData({
                            image,
                        })

                        await Api.post(Store.getters['dynamicForm/myUriBase'] + '/remove-temporary', request)
                            .then(() => {
                                return true
                            }, (error) => {
                                MyAlert.alertError(Api.displayError(error));
                                return false;
                            })
                    };
                }

                if (this.mySteps) {
                    this.builderObject.form.steps = r.data.steps ?? []
                }

                this.builderObject.method = this.myFormMethod;
                return;
            });
        },

        async submitForm(formData) {

            if (!formData) {
                return
            }

            let myFormMethod = 'create';
            let uriToSubmit = (this.myUri.split('/'))[0]; 
            if (this.myFormMethod == 'put' || this.myFormMethod == 'patch') {
                uriToSubmit = (this.myUri.split('/'))[0] + '/' + (this.myUri.split('/'))[1];
                myFormMethod = 'update';
            }

            if (this.myUriToSubmit) {
                uriToSubmit = this.myUriToSubmit
            }

            console.log('myFormMethod,uriToSubmit',myFormMethod,uriToSubmit, this.rollbackMask(formData));

            let bkpFormData = formData;

            //send data to backend
            return await Api[myFormMethod](uriToSubmit, this.rollbackMask(formData))
                .then((r) => {

                    if (r?.success) {
                        return this.fnRouteReturnSuccess(r);
                    }

                    console.log('### NÃO enviou', 'metodo==', myFormMethod, 'bkpFormData==', bkpFormData, 'r==', r, 'uriToSubmit==', uriToSubmit);
                    this.updateData(bkpFormData);
                });
        },

        rollbackMask(data) {
            let mySchema = this.builderObject.schema;
            let myReturn = data;

            for (const [key, value] of Object.entries(data)) {
                if (value && mySchema[key]['mask']) {
                    //para não ficar aplicando mask em valores que estão com mask
                    myReturn[key] = Masks.rollbackMask(value, mySchema[key]['mask'])
                }
            }
            return myReturn
        }
    }//fim

}
</script>

<style lang="scss">
@import '@/assets/scss/style.scss';
</style>