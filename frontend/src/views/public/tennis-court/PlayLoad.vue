<template>

  <templateView>

    <template v-slot:slotLineOne v-if="getMyLocation">

      <!-- Location -->
      <section class="container p-md-4">

        <form class="form" @submit.prevent="setMyLocationDistance()">
          <div class="d-flex justify-content-center align-items-center">
            <div class="me-2">
              <label for="inputmyLocationDistance" class="col-form-label">Resultados próximos a</label>
            </div>
            <div>
              <div class="input-group">
                <input name="inputmyLocationDistance" type="number" id="inputmyLocationDistance"
                  v-model="inputmyLocationDistance" required min="1" max="9999" step="1" class="form-control">
                <button class="btn border border-0 border-top border-bottom bg-white" type="submit"
                  id="btnCancelSearch">km</button>
                <button class="btn btn-secondary bl-0" type="submit" id="btnSubmitSetMyLocationDistance"><i
                    class="bi bi-search"></i></button>
              </div>
            </div>
          </div>
        </form>

        <label for="customRange1" class="sr-only">Example range</label>
        <input type="range" class="form-range" v-on:change="displaySliderValue()" :value="inputmyLocationDistance"
          id="customRange1" min="1" max="9999">

      </section>
      <!-- Fim Location -->

    </template>

    <template v-slot:slotTitlePage>
      <div style="background-color: #FFCC00;">
        <div class="d-flex justify-content-center align-items-center p-2">
          <div class="me-2">
            <IconFileSvg icon="quadra_lupa" height="50px" width="50px" bgColor="#083B54"></IconFileSvg>
          </div>
          <div>
            <h1>Quadras Disponíveis</h1>
          </div>
        </div>

        <!-- Pesquisa -->
        <section class="container p-md-4">
          <div class="col-md-6 col-lg-6 offset-md-3">
            <div class="row">
              <form class="form" @submit.prevent="filterItems()">
                <div class="input-group shadow-lg">
                  <input type="search" v-model="register.q" required class="form-control br-0"
                    placeholder="Pesquise por Nome, Cidade e etc" aria-label="Pesquise por quadras"
                    aria-describedby="pesquise-por-quadras" name="q" id="q" title="Use somente letras e números">
                  <button @click.prevent="filterItems({ q: 'all' })"
                    class="btn btn-outline-secondary border border-0 border-top border-bottom bg-white" type="button"
                    id="btnCancelSearch"><i class="bi bi-x"></i></button>
                  <button class="btn btn-secondary bl-0" type="submit" id="pesquise-por-quadras"><i
                      class="bi bi-search"></i></button>
                </div>
              </form>
            </div>
          </div>
        </section>
        <!-- Fim Pesquisa -->

      </div>
    </template>

    <template v-slot:slotPageComponet>

      <!-- aside filters -->
      <NavFiltersComponent></NavFiltersComponent>
      <!-- fim aside filters -->

      <main class="container-fluid">
        <div class="row flex-nowrap py-4">

          <!-- list items -->
          <section v-if="items?.success">

            <div class="row row-cols-1 row-cols-md-4 g-4 offset-md-1">

              <template v-for="(vDados, i) in items.data.data" :key="i">


                <div class="card m-lg-4 py-3 p-sm-1" v-if="vDados.id">

                  <div class="bg-image hover-overlay ripple rounded-0 mb-2" data-bs-ripple-color="light">
                    <router-link :to="{ name: 'tennisCourtShow', params: { id: vDados.id } }">

                      <img class="img-fluid rounded " src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAGQCAYAAAByNR6YAAAAAXNSR0IArs4c6QAAIABJREFUeF7t3WmP5NTZBuAiCSHAEIZAAsMWYAgB8f//BV+zsAxhGbYA2YEsLK/ueuWOc3CXXd13EUJfltBIdNVT9uVH8q1z7OM7Xnrppa92NgIECBAgQIAAgZrAHQJWzVIhAgQIECBAgMBeQMDSCAQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9AABAgQIECBAoCwgYJVBlSNAgAABAgQICFh6gAABAgQIECBQFhCwyqDKESBAgAABAgQELD1AgAABAgQIECgLCFhlUOUIECBAgAABAgKWHiBAgAABAgQIlAUErDKocgQIECBAgAABAUsPECBAgAABAgTKAgJWGVQ5AgQIECBAgICApQcIECBAgAABAmUBAasMqhwBAgQIECBAQMDSAwQIECBAgACBsoCAVQZVjgABAgQIECAgYOkBAgQIECBAgEBZQMAqgypHgAABAgQIEBCw9ACBb0jgRz/60e7hhx/e/fjHP97deeeduzvuuGP/y1999dXu888/3/3tb3/bffjhh7u//vWvR+3Rgw8+uHvooYd2d9999+773//+2Xe/+OKL3SeffLKv+ac//emomvnwvffeu/vZz362u++++3Y/+MEPzvb3yy+/3P3jH//Y/eEPf9jXzu9cpS0uTz/99O6uu+7aH3bO1yuvvHIUQfog5y01vve97531QSxT7yJ9kCKn6oWjDs6HCRDYCwhYGoHANyDwxBNP7C9+8wC09LMJLwkut2/fXg0uP/zhD3dPPvnkPrBNYW2pZgLcX/7yl91bb721++c//7npaB999NF9uFrb3wSt7OtFAtymHfkWfugXv/jF3nzajglYCWfphfx7aDumD1LnlL3wLTwFdonA/4SAgPU/cZrs5P+qQALKz3/+893169cPhqD58SUQJWS98cYb5x52RsNS99q1a5tpMkL25ptv7v7+978f/E5CW8LgNLKy9gP/+te/9iEr+/xd3xI8M/o0t9kasDISGNucuy1b+iDBNefs0CjhKXthy376DAECywICls4gcEKBhKCElfkI02effbafAkogSQD7yU9+sp/im6acsjsZwfjggw9277777uLePfXUU/vvTXXz+T//+c9nU0v333//vmb+nU9FrgW3n/70p7vHHnvsP0auPv30093vf//7/cU+U5upm2PKtOG05Zhef/311fB2QuqTl45lAlJGi+bbloCV8/zMM8/8x8hXQlPOx8cff7yfys35jH9Gt+bnNfbvvPPOucd3ql44OagfIPAdFxCwvuMn2OH99wRywcx00BREEoJyMc1U3bgtXYATbHJvzzh6kYDz+OOPn4Wg/D0X4IS2cXvkkUd2+W+a6su9Xm+//fbiaFNGQhICci9XtkMjaeNoTD770UcfLR7bf+8M9H556fxM1bcErIx85TxMwSlTtemDhOJxG0cQMw37u9/9bh/Cxu1UvdCTU4nA1RUQsK7uuXfkJxZ49tln9yNIa2Fl2o2MXCTgTCMkCU4JQwll822suxZscsHOhXi6uOei/tprry2GsRs3bpxNf50X8KYvjgHyUBA4MfXJy2dUL1ODS/e6rQWshLPnnntud8899+z3M0H7vffe273//vvn7vfWc7z1c9MPbe2Fk4P6AQJXQEDAugIn2SF+8wIJVpkezJRatoSPhJq1+58SsB544IH9RXiaJpxfiDNylCfYprq5/ymjG4eePByDW0ZPMp03jogkBKT+FAjzu+dNUU6i8wv8luDwzZ+Jy/9i7p/LuZxGIhM8M507jQquBaxMp2Ykc/p8plNffvnlg/dVJbwmDE3fWQq7p+yFy6upQICAgKUHCJxAIFN4eQpvGvHI9N3S1OCxP51ppvko09rFfap/8+bN/Y32542gjCFsS3BLrXF/cp/WrVu3jj2sb+3nE3ASIqeHCRKUc09Upvy2BqwEpdxbNW0ZccyN62vbCy+8cDbqland9M8f//jHs6+dqhfW9svfCRDYJiBgbXPyKQJHCcwf5V+6OB5VbPbh3NCcEZFp2xrcxsCXacf5U4qZQswoy/R0XEZMfvOb36zuZkbqsk/T6E5G6H7729+uLjExFs5oTOrMbyDPU48Z9Tv0BF1G8zLaM22H7m1aPZiFD8yn1KYRuoTP+YjUWsidjwweM8o3jWZmt3KP2ziieKpeuIiT7xAg8HUBAUtXECgLZGTj+eefP3sc/6KhY2m3Lnqxzmha7iOaAtQYCsabsLeOROXG+IzwTE9Abh35Wjq2cUQmoSKjRVkCYsvnE15ys3++09jGG8gzepSp1XHKby1gvfjii2e9cEzYHkPxOPJ1ql5o2KlBgICFRvUAgbpAFqHM6MJ0n9T8Ary00naCRIJJFgPNzc+HFgOdX6zPuwl+6YDGUJDQ96tf/erso7nHKIFi2rZOY+XzF92npf2cj9rk73HJdNr4tN044rVl7bBjTvT4ROX8HrpjAtZF7pOa9nMMvWOQu6j7Wi8c4+SzBAicL2AES3cQKAuMF7AEpzwNmGmlXHAPrbqe0JSpoKUnzC4zWjRe6MdRtbXpqENE40hKjjUB7SLbGGxSI8Ei93VNU4VLSya01+Gary01jowdE7CWplDnwfaQ0aHfOWUvXOS8+Q4BAl8XELB0BYGywDgdl/ud8oj+tL7U2s/lgr60uORlRkPWvnuRJwin47jMd5csxsVOR49xyYRMu2WUq/W6nvH3sxhontSctmMC1mVGiw59d+18Huqxy3x3rXf9nQCBfwsIWLqBQFlgnNpJAJhuAs8oTIJAQldGZvL0XpZlGFdGX1o89DIXxrXvXiYkXea759GPq5Nn2jQ35U+vHpov3rq20vkxp3ccQVsaGROwjhH1WQJXV0DAurrn3pGfSGAMWNPP5D6eTJ8trd6doJX7oOajXOPFfS0kXWbU4jIh6TLfPW+fl6YK45b72qYFO/PdTL+++uqrtTM5nyo9b4V8AavGrRCB77SAgPWdPr0O7r8hsBSwtkxjjYuTjo/0X6WAlfM2rhQ/nsuti7du7YH51O6hm+YFrK2iPkfgagsIWFf7/Dv6EwgsBazxPp7zfnZ8mm++XMJVC1gxGpcqmNwOvX/xIqc0I4hZU2tabuLQTfMC1kWEfYfA1RMQsK7eOXfEJxYY13M6Zn2mccHPXOh//etf7/d4XF/rmDWn1p4inC9aubSo5SGy5lOE4+8sPTGY/cs9bFtWQ996qucLw66Ft2MC1rhkx7g8xqH9O/Q7p+yFrWY+R4DAYQEBS4cQKAuMF8ZMD+YG7aV7r8afXrsgn2rto2/LOlhLp2L+vsPp7+e9sPoipzIjjnmRcxZh3RLejglYlxl1tA7WRc6m7xD49ggIWN+ec2FPviMCY0i67EjTfN2kcbRo68rlx67kPq1avnZKLrMe01rt/H0efuafn16EvfYy6i2/MTfd8vm1z4yLtI4ruW8N28eu5N7qhbXj83cCBLYJCFjbnHyKwGaBMXQcs+L62sKU85GmtVfJzHd47WKdG8rz3r3pBcZ5D+DLL7+8esxr+7ta4MAHlt5POP94672Dpw5Y8+nHY6aL1xZ/PVUvXOac+S4BAv8WELB0A4ETCIxLFxx6p97858f7t8bXo4x/3/rOwLX31uUm71zQp5ctJ7zkvXuffPLJQZ2LvsNwjXzp3qssyZAtI4TTlv+X/Tz0Qui13zp1wEpwzeKl07blBd05/l/+8pdny3YsvcPwVL2w5uXvBAhsExCwtjn5FIGjBMbVxj/99NPdK6+8shoExnfxjRfj8Z6eLUFoDE/nTVmuhbAlgPn9UcfeHH8INH6Z1pxeTj2NVuU7GbmZ3vOY3/zggw/2L3m+6BbTKVhuqZHPJtxM+5bRvvmrgXIj+zyYjvdsbemFcUQxDztkRHEeJE/ZC1scfIYAgcMCApYOIXACgTHUnPf6m/lPX79+fR8eplXKz1s7aww1a0/UpWYu8tM7EM+7QXwcEVl7v1+eeMzU4zStuCXsbaFeWg9svlr7qV+Vs7aPx9zknlrxSXidFkjdcv/YfFoxNc4b9TpVL6wZ+DsBAusCAta6kU8QuJDAGGwy+pCbshMWxi2jEZlKyv1b03ZeEBqDTepmFOe99977Wt0bN27sn5CbQlA+e/v27cWXMY+rp2d0KFNweQ/fOAWXEJSXV0/rRuWHE/RyA/dltuxnQsO1a9fOyozTgPnMzZs39y/OnraMIr322murI4SX2bfpu8cGrHxv6fVJGXVbein22DeHHpI4VS80nNQgcNUFBKyr3gGO/2QCS697SWjJ9FFGJLL4aEa6csHO+winkavs0NoN3ONUYuomkOWCnX8TgHLxzb/TyFXqri14Oj5tmO9kJGva30zNpe747sS10a6tyAmZqT/tc8JF1rsal7gYR/ty/Dn2t956a+tPXfhzFwlYS8Fxei9lbNMTmRbMvVrpien4t0yBnqoXLgzkiwQI7AUELI1A4IQCSyNTaz+XqcGMdOXCe962FN7W6maUJ2El9wgd2sYXLa/VTQjKqFjC22W2cTRmLVyMT0YeGp27zH6N371IwEqNhN0EyK33e+X48xBDztmhm/hP2QtNN7UIXDUBAeuqnXHH+40L5AKY6bSErflo0tKO5P16CVdbwsrWutNUX0Z3MjK2Zcv+JkhMU4vnfSf7m3CVIHCZbSkk5AnKW7dunRsuxnub8vutkbRDx3LRgJWa6YEEw/kLq5d+K/dppQdiu+UJyVP2wmXOq+8SuMoCAtZVPvuO/RsVyLRWpoBycU04mMJWLqYJKrmgZtRqywV1vuO54Gf05+677/6PQJQ6CRyZOsv9UcdumarKlGFCQaYv5/uboJZQ9f777x+9v0v7MY6anTc1OH53fCH0oZc0H3v8533+MgFrqpn74lIn97BNTyNm33PO8pRh7qmblqU4Zr9P1QvH7IPPEiDw/wIClk4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAAAQIECAhYeoAAAQIECBAgUBYQsMqgyhEgQIAAAQIEBCw9QIAAAQIECBAoCwhYZVDlCBAgQIAAAQIClh4gQIAAAQIECJQFBKwyqHIECBAgQIAAAQFLDxAgQIAAAQIEygICVhlUOQIECBAgQICAgKUHCBAgQIAAAQJlAQGrDKocAQIECBAgQEDA0gMECBAgQIAAgbKAgFUGVY4AAQIECBAgIGDpAQIECBAgQIBAWUDAKoMqR4AAAQIECBAQsPQAAQIECBAgQKAsIGCVQZUjQIAic8Q3AAAAGElEQVQAAQIECAhYeoAAAQIECBAgUBb4P1dRPzH7+HphAAAAAElFTkSuQmCC"
                        alt="Card image cap" />

                      <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </router-link>

                  </div>

                  <h3 class="card-title font-weight-bold mb-1 fs-5 text text-center">
                    <router-link :to="{ name: 'tennisCourtShow', params: { id: vDados.id } }">{{ vDados.name
                      }}</router-link>
                  </h3>
                  <div class="align-items-center"><i class="bi bi-geo-alt me-1"></i><strong>{{ vDados.city
                      }}</strong>/{{ vDados.state_code }}</div>
                  <div class="card-body d-flex flex-row">
                    <div class="card-text">
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-half"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i class="bi bi-star"></i></button>
                      </div>
                    </div>
                  </div>

                  <div class="card-body">

                    <div class="d-flex flex-sm-column justify-content-between align-items-sm-center">

                      <!-- involvement -->
                      <tennisCourtInvolvementComponent :item="vDados"></tennisCourtInvolvementComponent>
                      <!-- involvement -->

                      <div>
                        <router-link class="btn btn-outline-secondary" role="button" :to="{ name: 'tennisCourtShow', params: { id: vDados.id } }"><i class="bi bi-calendar-check me-2"></i>Reservar</router-link>
                      </div>

                    </div>
                  </div>
                </div>
              </template>
            </div>

          </section>

          <section v-else>
            <div class="alert alert-warning" role="alert">Nenhum item para ser exibido</div>
          </section>
          <!-- fim list items -->

        </div>

        <div class="row flex-nowrap py-4 ">
          <!-- pagination -->
          <section v-if="items?.data?.links">
            <div class="d-flex justify-content-center">
              <nav aria-label="paginationTable">
                <ul class="pagination">

                  <template v-if="items?.data?.links">
                    <template v-for="(vLinks, iLinks) in items.data.links" :key="iLinks">
                      <template v-if="vLinks.url">
                        <li :class="vLinks.active ? 'page-item active' : 'page-item'">
                          <a class="page-link" href="#" @click="paginateItens(vLinks.url)">
                            <i class="bi bi-skip-start-fill" v-if="vLinks.label == 'Anterior'"></i>
                            <i class="bi bi-skip-end-fill" v-else-if="vLinks.label == 'Próximo'"></i>
                            <i class="bi" v-else>{{ vLinks.label }}</i>
                          </a>
                        </li>
                      </template>
                    </template>
                  </template>

                </ul>
              </nav>
            </div>
          </section>
          <!-- fim pagination -->
        </div>
      </main>


    </template>
  </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

import { mapGetters } from "vuex";
import store from "@/store";

import helpers from "@/helpers/helpers.js";

export default {
  name: 'TennisCourtList',
  components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/template/Template.vue')
    ),
    IconFileSvg: defineAsyncComponent(() =>
      import('@/components/icons/IconFileSvg.vue')
    ),
    tennisCourtInvolvementComponent: defineAsyncComponent(() =>
      import('@/components/tennisCourt/TennisCourtInvolvementComponent.vue')
    ),
  },
  data() {
    return {
      //items: {},
      pages: {},
      apiPath: 'tennis-court?page=1',

      itemsToRender: {},
      inputmyLocationDistance: null,

      register: {
        q: null,
      },
      error: "",
    }
  },
  async mounted() {
    this.inputmyLocationDistance = this.myLocationDistance;
    this.init();
  },
  computed: {
    ...mapGetters('tennisCourt', ['items', 'arrayFilters', 'filtersEnable', 'myLocationDistance', 'getMyLocation', 'queryBuild']),
  },
  methods: {

    ///
    async init() {
      if (!this.items?.success) {
        return await this.filterItems();
      }
      //return this.searchBy();
    },

    ///
    async filterItems(q = {}) {
      if (q?.q == 'all') {
        this.register.q = null;
      }

      if (this.register.q && typeof this.register.q === 'string') {
        q = { q: helpers.encodeHTML(this.register.q) };
      }

      await store.dispatch('tennisCourt/filterItems', q).then(async () => {
        await store.dispatch('tennisCourt/findInObject');
      });
    },

    ///
    async paginateItens(path = this.apiPath) {

      //console.log('path.split', path.split(`${process.env.VUE_APP_API_URL}`))
      /* if (path == this.path) {
        return;
      } */

      await store.dispatch('tennisCourt/filterItems', { pagination: path }).then(async () => {
        await store.dispatch('tennisCourt/findInObject');
      });
    },

    ///
    async page(param) {
      console.log(param, this.$router.currentRoute.value.path);
      this.$router.push({
        path: this.$router.currentRoute.value.path,
        query: { page: param }
      });
      this.$router.go(1);
    },

    ///
    setMyLocationDistance() {
      //console.log('this.myLocationDistance', {distance:this.inputmyLocationDistance})
      //let r = { distance: this.inputmyLocationDistance };
      return this.filterItems({ distance: this.inputmyLocationDistance });
    },

    ///
    async fechItemsCloseToMe() {
      await store.dispatch('tennisCourt/setMyLocation').then(async (response) => {

        if (!response || response?.code == 1) {
          this.geolocation = false
          return;
        }

        this.geolocation = true;

        await store.dispatch('tennisCourt/fechItemsCloseToMe').then(() => {
          return this.goToRoute('tennisCourtPlayLoad');
        });
      });
    },

    ///
    displaySliderValue() {
      let eSlider = document.querySelector("#customRange1");
      this.inputmyLocationDistance = eSlider.value ?? 1
      this.setMyLocationDistance();
    },

    ///fim
  }
}

</script>

<style lang="scss" scoped>
.card a {
  text-decoration: none;
}
</style>
