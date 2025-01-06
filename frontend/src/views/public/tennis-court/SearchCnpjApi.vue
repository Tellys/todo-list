<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <div class="container">
                <h1>Auto Complete CNPJ</h1>
                <div class="col-md-7 col-lg-6 col-xl-6 offset-xl-3">
                    <form @submit.prevent="functionAction()">

                        <!-- CNPJ input -->
                        <div class="form-outline mb-4">
                            <input type="number" step="1" min="0" id="cpf" class="form-control form-control-lg"
                                v-model="register.cpf" required />
                            <label class="form-label" for="price">CNPJ</label>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="col-12 btn btn-primary btn-lg btn-block">Próximo</button>

                    </form>
                </div>
            </div>
        </template>
    </templateView>
</template>

<script>
import router from '@/router';
import BrasilApi from '@/services/BrasilApi';
import Api from '@/services/Api';
import MyAlert from '@/services/MyAlert';
import { defineAsyncComponent } from 'vue';

export default {
    name: 'TennisCourtSearchCnpjApi',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),},
    data() {
        return {
            register: {},
            items: {},
            error: "",
            itemCreated: null
        }
    },
    created() {
        this.init();
    },
    methods: {
        async init() {
            return;
        },
        async functionAction() {
            this.items = await BrasilApi.cnpj(this.register.cpf);

            if (this.items) {

                this.items.users_level_id = 4;

                this.alertConfirm(
                    'Confirma os dados abaixo?',
                    '<strong>Razão Social:</strong> ' + this.items.razao_social + ' <br><strong>Nome Fantasia:</strong> ' + (this.items.nome_fantasia ?? 'vazio')
                )
            }

        },
        async alertConfirm(title = 'Titulo', text = 'texto') {
            return await MyAlert.alertConfirm(title, text)
                .then((result) => {
                    if (result) {
                        return this.create();
                    }
                    return false;
                })
        },
        async create() {

            let r = {};

            for (const [k, v] of Object.entries(this.items)) {
                switch (k) {
                    case 'uf':
                        r['state'] = v;
                        break;
                    case 'cep':
                        r['zip_code'] = v;
                        break;
                    case 'municipio':
                        r['city'] = v;
                        break;
                    case 'cnpj':
                        r['cpf'] = v;
                        break;
                    case 'bairro':
                        r['address_neighborhood'] = v;
                        break;
                    case 'numero':
                        r['address_num'] = v;
                        break;
                    case 'logradouro':
                        r['address'] = v;
                        break;
                    case 'complemento':
                        r['address_complement'] = v;
                        break;
                    case 'ddd_telefone_1':
                        r['cell_phone'] = v;
                        break;
                    case 'ddd_telefone_2':
                        r['phone'] = v;
                        break;
                    case 'razao_social':
                        r['name'] = v;
                        r['name_corporate'] = v;
                        break;
                    case 'data_inicio_atividade':
                        r['birthday'] = v;
                        break;

                    default:
                        break;
                }
            }

            r['users_level_id'] = 4;

            return await Api.post('tennis-court', r)
                .then(async (response) => {
                    console.log(response);

                    if (response.status == 200) {
                        this.itemCreated = response.data;
                        return await MyAlert.alertCountDown('Aguarde... Registrando CNAE', 'Sucesso', 3000, 'success')
                            .then(() => {
                                this.userCnae();
                            });
                    }

                    MyAlert.alertError(Api.displayError(response));
                    return false;

                }), (error) => {
                    console.log(error);
                    MyAlert.alertError(error.statusText);
                    return false;
                };
        },

        async userCnae() {
            await Api.create('user-cnae', this.items)
                .then(async (response) => {
                    console.log('user-cnae', this.items, response);

                    if (response.status == 200) {
                        await MyAlert.alertCountDown('Redirecionando para visualização dos dados', 'Sucesso!!', 3000, 'success')
                            .then(() => {
                                router.push('/tennis-court/' + this.itemCreated.id);
                                return true;
                            })
                        return false;
                    }

                    await MyAlert.alertCountDown('Não foi possivel cadastrar os CNAE automaticamente <br/> Redirecionando você para o controle manual', 'Atenção!!', 5000)
                        .then(() => {
                            router.push('/tennis-court/' + this.itemCreated.id + '/edit');
                            return false;
                        })

                }), (error) => {
                    console.log(error);
                    MyAlert.alertError('Erro, tente novamente');
                    return false;
                };
        },
    }
}
</script>