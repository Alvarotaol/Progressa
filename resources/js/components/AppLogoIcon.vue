<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="64" height="64" rx="12" fill="#F2F6FF" />
        <path d="M20 48V16H34C39.523 16 44 20.477 44 26C44 31.523 39.523 36 34 36H28V48H20Z" fill="#4F46E5" />
        <path d="M28 28L36 20" stroke="#10B981" stroke-width="3" stroke-linecap="round" />
    </svg>

</template>
