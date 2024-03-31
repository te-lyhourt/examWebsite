<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <td
    :class="cn('p-4 align-middle [&:has([role=checkbox])]:pr-0 overflow-hidden max-w-[200px]', props.class)"
  >
    <slot />
  </td>
</template>
