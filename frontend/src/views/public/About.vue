<template>
  <templateView>
    <template v-slot:slotPageComponet>
      <div class="text-center">
        <h1>Página About</h1>
        <h4>"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."</h4>
        <blockquote>"There is no one who loves pain itself, who seeks after it and wants to have it, simply because it
          is pain..."</blockquote>
      </div>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque risus velit, cursus eu blandit viverra,
        elementum ac nunc. Donec sit amet velit ut felis molestie aliquam non vitae tortor. Morbi placerat libero nec
        felis ultricies aliquam placerat ac ex. Duis sit amet maximus enim, ac auctor est. Sed bibendum leo id ultricies
        sollicitudin. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas
        posuere arcu nisi, vitae luctus lorem posuere et. Duis at malesuada elit.
      </p>
      <p>
        Pellentesque lobortis leo quis porttitor faucibus. Nunc vehicula aliquet magna, a commodo leo scelerisque id.
        Nulla facilisi. Vivamus finibus molestie massa, sed vestibulum risus pulvinar eu. Nulla libero tellus, bibendum
        in sem vitae, fermentum placerat quam. Nullam nec tempor sapien. Aliquam consectetur magna quis turpis volutpat,
        nec facilisis odio condimentum.
      </p>
      <p>
        In interdum tellus ac mi hendrerit efficitur. Integer tempus nibh ut mauris dictum suscipit. Suspendisse ut
        velit ut augue lobortis pharetra non quis augue. Aliquam at faucibus leo. Nullam ut tincidunt risus, eu
        pellentesque justo. Mauris ut nibh quis diam ornare hendrerit sit amet vitae erat. Maecenas convallis pretium
        orci at porttitor. Ut maximus nec neque vitae pulvinar. Integer ullamcorper ornare tortor. Morbi fringilla ut
        libero sit amet faucibus. Nam nisi massa, laoreet quis fermentum in, ullamcorper non urna.
      </p>
      <p>
        Ut vehicula laoreet mi, et semper nulla pellentesque posuere. Suspendisse facilisis elementum lacus et varius.
        Nullam et commodo eros, vitae cursus ante. Proin varius ex sed ornare iaculis. Etiam faucibus, nunc nec vehicula
        eleifend, ex eros rhoncus elit, sed placerat lacus metus sed ligula. In metus quam, blandit quis sapien vitae,
        rutrum lobortis lacus. Aenean sagittis aliquet euismod. Donec ac massa vitae lorem euismod fringilla vel sodales
        neque. Cras tempor tempor vehicula. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam auctor a
        ante a efficitur. Duis ac quam sit amet ipsum efficitur suscipit. Donec aliquet ultrices erat a lobortis. In
        vulputate, ex nec varius vestibulum, orci arcu ornare augue, in dictum leo nulla congue orci.
      </p>
      <p>
        Nam tincidunt elementum rhoncus. Donec malesuada tempus nisl. Vivamus interdum nulla sed nulla malesuada
        tincidunt. Suspendisse consequat dui tortor, ut ultricies metus ultrices eu. Fusce vehicula sit amet nunc eu
        aliquam. Etiam hendrerit varius accumsan. Nullam ac sapien id massa tempus convallis. Pellentesque bibendum sem
        ut consequat suscipit. Vestibulum viverra a dui in maximus. Ut viverra, tortor ac porta auctor, enim dui aliquam
        magna, sed interdum leo ante sit amet turpis. Praesent eget odio gravida, consequat augue in, venenatis ex.
        Morbi bibendum cursus euismod. In sem nulla, porttitor non purus sit amet, gravida congue ligula. Integer
        congue, nibh a tempus imperdiet, purus nunc tincidunt orci, eget tempus dui neque eget tortor. Donec mattis
        neque eget velit bibendum, quis porttitor orci sagittis. In hac habitasse platea dictumst.
      </p>

    </template>
  </templateView>
</template>

<script>
// @ is an alias to /src
import templateView from '@/views/template/Template.vue';

export default {
  name: 'aboutView',
  components: {
    templateView,
  }
}
</script>
