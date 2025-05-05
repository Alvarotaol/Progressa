<script setup lang="ts">
import { Tag } from '@/types';


interface Props {
	tag: Tag
}

const props = defineProps<Props>();

</script>

<template>
	<span :key="props.tag.label" class="text-xs font-medium px-2 py-1 rounded-full border hover:opacity-80"
		:style="{ backgroundColor: props.tag.color, color: '#333', borderColor: '#00000055' }">
		{{ tag.label }}
	</span>
</template>