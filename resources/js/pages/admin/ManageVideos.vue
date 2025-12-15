<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@/types'

const props = defineProps<{ videos: Array<any> }>()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Manage Videos', href: '/dashboard/videos' }
]
</script>

<template>
  <Head title="Manage Videos" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-4">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Manage Videos</h2>
        <span class="text-sm text-muted-foreground">Total: {{ props.videos.length }}</span>
      </div>
      <div class="overflow-x-auto rounded-lg border border-sidebar-border/70">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
          <thead class="bg-muted/40">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Short Code</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Views</th>
              <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Created</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="video in props.videos" :key="video.id">
              <td class="px-4 py-3 text-sm font-mono">{{ video.short_code }}</td>
              <td class="px-4 py-3 text-sm">{{ video.original_name }}</td>
              <td class="px-4 py-3 text-sm capitalize">{{ video.status }}</td>
              <td class="px-4 py-3 text-sm">{{ video.views }}</td>
              <td class="px-4 py-3 text-sm">{{ new Date(video.created_at).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
