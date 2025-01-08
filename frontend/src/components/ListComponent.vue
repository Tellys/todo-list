<template>

  <div class="d-flex justify-content-end mb-3">

    <div class="p-2 flex-grow-1">
      <form class="form" @submit.prevent="searchBy()">
        <div class="input-group shadow-lg">
          <input type="search" v-model="register.search" required class="form-control br-0 " placeholder="Pesquisar"
            aria-label="Pesquisar" aria-describedby="Pesquisar" name="search" id="search">
          <button @click="getItens(apiPath)" class="btn btn-outline-secondary border border-0 border-top border-bottom"
            type="button" id="btnCancelSearch"><i class="bi bi-x"></i></button>
          <button class="btn btn-secondary border border-0 border-top border-bottom" type="submit" id="btnSearch"><i
              class="bi bi-search"></i></button>
        </div>
      </form>
    </div>

    <div class="p-2">
      <button type="button" class="btn btn-primary" @click="getItens(apiPath)">
        <i class="bi bi-list-ul"></i> Listar Todos <span class="badge text-bg-light">{{ items?.data?.meta?.data
          }}</span>
      </button>
    </div>

    <div class="p-2">
      <button type="button" class="btn btn-outline-secondary" @click="getItens(atualApiPath, '&trash=true')"
        v-if="items?.data?.meta?.trash">
        <i class="bi bi-trash"></i> Lixeira <span class="badge text-bg-danger">{{ items?.data?.meta?.trash }}</span>
      </button>

      <button type="button" class="btn btn-outline-secondary disabled" v-else>
        <i class="bi bi-trash"></i> Lixeira <span class="badge text-bg-danger">0</span>
      </button>
    </div>
  </div>

  <table class="table caption-top table-striped table-hover align-middle" v-if="items?.success">
    <template v-if="items.data.heads">
      <thead>
        <tr>

          <template v-for="(vHeads, iVHeads) in items.data.heads" :key="iVHeads">
            <th scope="col">
              <div class="text-capitalize d-flex flex-row align-items-center justify-content-center">
                <div class="text-start" v-if="iVHeads == 'id'">{{ vHeads.label }}</div>
                <div v-else>{{ vHeads.label }}</div>
                <div >
                  <button :id="'head' + iVHeads + 'Down'"
                    @click.prevent="getItens(atualApiPath, '&oderBy[' + iVHeads + ']=asc', ['head' + iVHeads + 'Down', 'head' + iVHeads + 'Up'])"
                    class="btn btn-sm p-1 " type="button"><i class="bi bi-sort-alpha-up-alt"></i></button>

                  <button :id="'head' + iVHeads + 'Up'"
                    @click.prevent="getItens(atualApiPath, '&oderBy[' + iVHeads + ']=desc', ['head' + iVHeads + 'Up', 'head' + iVHeads + 'Down'])"
                    class="btn text-danger  btn-sm p-1 d-none" type="button"><i class="bi bi-sort-alpha-down-alt"></i></button>
                </div>
              </div>
            </th>
          </template>

          <th scope="col" class="text-end ">Cmd</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <tr v-for="(vDados, iVDados) in items.data.db.data" :key="iVDados" class="text-start">
          <template v-if="vDados.id">

            <template v-for="(vHeads2, iVHeads2) in items.data.heads" :key="iVHeads2">
              <th class="text-start" scope="row" v-if="iVHeads2 == 'id'">{{ vDados[iVHeads2] }}</th>
              <td v-else-if="iVHeads2.toLowerCase() == 'icon_id'"><i :class="vDados[iVHeads2]"></i></td>
              <td v-else>{{ vDados[iVHeads2] }}</td>
            </template>

            <td class="text-end">
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <button v-if="!items.data?.comandos?.restore" type="button" class="btn btn-outline-primary"
                  title="Visualizar Item" @click="viewItem(vDados.id)">Ver</button>
                <button v-if="items.data?.comandos?.destroy" type="button" class="btn btn-danger"
                  title="Coloar Item na Lixeira" @click="deleteItem(vDados.id)"><i class="bi bi-trash"></i></button>
                <button v-if="items.data?.comandos?.edit" type="button" class="btn btn-outline-secondary"
                  title="Editar Item" @click="editItem(vDados.id)"><i class="bi bi-pencil-fill"></i></button>
                <button v-if="items.data?.comandos?.restore" type="button" class="btn btn-outline-info"
                  title="Reciclar Item" @click="restoreItem(vDados.id)"><i class="bi bi-recycle"></i></button>
                <button v-if="items.data?.comandos?.forceDelete" type="button" class="btn btn-dark"
                  title="Excluir definitivamente" @click="forceDeleteItem(vDados.id)"><i
                    class="bi bi-trash"></i></button>
              </div>
            </td>
          </template>
        </tr>
      </tbody>
    </template>
  </table>

  <div class="alert alert-warning" role="alert" v-else>Nenhum item para ser exibido</div>

  <div class="d-flex justify-content-center">
    <nav aria-label="paginationTable">
      <ul class="pagination">
        <li class="page-item" v-if="items?.data?.db?.current_page - 1 > 0">
          <!-- <a class="page-link" href="?page=" @click="page(items?.first_page)" v-if="items?.first_page"> -->
          <a class="page-link" href="#" @click="getItens(items?.data?.db?.first_page_url)">
            <i class="bi bi-skip-backward-fill"></i>
          </a>
        </li>

        <template v-if="items?.data?.db?.links">
          <template v-for="(vLinks, iLinks) in items?.data?.db?.links" :key="iLinks">
            <template v-if="vLinks.url">
              <li :class="vLinks.active ? 'page-item active' : 'page-item'">
                <a class="page-link" href="#" @click="getItens(vLinks.url)">
                  <i class="bi bi-skip-start-fill" v-if="vLinks.label == 'Anterior'"></i>
                  <i class="bi bi-skip-end-fill" v-else-if="vLinks.label == 'Próximo'"></i>
                  <i class="bi" v-else>{{ vLinks.label }}</i>
                </a>
              </li>
            </template>
          </template>
        </template>

        <li class="page-item" v-if="items?.data?.db?.last_page_url">
          <a class="page-link" href="#" @click="getItens(items?.data?.db?.last_page_url)" title="Última Página">
            <i class="bi bi-skip-forward-fill"></i>
          </a>
        </li>
      </ul>
    </nav>
  </div>

</template>

<script>
import Api from '@/services/Api';
import { split } from 'postcss/lib/list';

export default {
  name: 'ListComponent',
  props: {
    myUri: String,
  },
  data() {
    return {
      items: null,
      pages: {},
      apiPath: null,
      atualApiPath: null,

      register: {},
      error: "",
      id: this.$route.params.id,
    }
  },
  mounted() {
    // this.apiPath = this.myUri + '/list?page=1';
    // this.atualApiPath = this.myUri + '/list?page=1';
    this.apiPath = this.myUri + '/list?page=1';
    this.atualApiPath = this.myUri + '/list?page=1';

    this.getItens();
  },
  methods: {
    async getItens(path = this.apiPath, param = '', idTag = []) {

      if (idTag.length == 2) {
        //console.log(document.getElementById(idTag[0]).classList.add('d-none'));
        document.getElementById(idTag[0]).classList.add('d-none')
        document.getElementById(idTag[1]).classList.remove('d-none')
      }

      //evitar recarregamento de mesma url
      if (this.items && path + param == this.atualApiPath) {
        return;
      }

      //evitar recarregamento de mesma url + param
      if (path.search(param) > 0) {
        return;
      }

      if (param.length && (path + param) == this.atualApiPath) {
        return;
      }

      // if (!param.length) {
      //   this.apiPath = path;
      // }

      this.atualApiPath = path + param;
      this.items = await Api.get(this.atualApiPath);
      console.log(this.items);
      return this.items
    },
    async page(param) {
      //console.log(param, this.$router.currentRoute.value.path);
      this.$router.push({
        path: this.$router.currentRoute.value.path,
        query: { page: param }
      });
      this.$router.go(1);
    },
    async refresh() {
      return this.items = await Api.get(this.atualApiPath);
    },
    async editItem(id) {
      this.$router.push(this.getUriBase() + '/' + id + '/edit');
    },
    async viewItem(id) {
      this.$router.push(this.getUriBase() + '/' + id);
      //this.$router.push('/tennis-court/' + id + '/edit');
    },
    async deleteItem(id) {
      return await Api.delete(this.myUri + '/' + id)
        .then(() => {
          this.refresh();
        });
    },
    async forceDeleteItem(id) {
      return await Api.delete(this.myUri + '/' + id + '/' + 'force-delete')
        .then(() => {
          this.refresh();
        });
    },
    async restoreItem(id) {
      return await Api.update(this.myUri + '/' + id + '/' + 'restore')
        .then(() => {
          this.refresh();
        });
    },
    getUriBase() {
      var l = split(this.$router.currentRoute.value.path, "/");
      l.pop();

      var m = '/';
      for (let i = 0; i < l.length; i = i + 1) {
        m += l[i] + '/'
      }
      return m.substring(0, m.length - 1);
    },
    async searchBy() {

      let myParam = this.atualApiPath.search("/?/") ? '&search=' : '?search=';

      this.atualApiPath = this.atualApiPath + myParam + this.register.search

      this.items = await Api.get(this.atualApiPath);
      console.log(this.items);
      return this.items
    },
  },
}
</script>