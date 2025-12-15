<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps<{
  video: {
    name: string
    playback_url: string
    share_url: string
    status: string
    views: number
  }
}>()

const isPlaying = ref(false)
const isMuted = ref(false)
const progress = ref(0)
const videoPlayer = ref<HTMLVideoElement | null>(null)
const copied = ref(false)
const playAdShown = ref(false)

const statusBadge = computed(() => {
  const base = 'px-3 py-1 rounded-full text-xs capitalize'
  if (props.video.status === 'ready') return `${base} bg-green-500/20 text-green-200`
  if (props.video.status === 'failed') return `${base} bg-red-500/20 text-red-200`
  return `${base} bg-yellow-500/20 text-yellow-200`
})

const copyLink = async () => {
  await navigator.clipboard.writeText(props.video.share_url)
  copied.value = true
  setTimeout(() => (copied.value = false), 2000)
}

const togglePlay = () => {
  if (!videoPlayer.value) return
  if (videoPlayer.value.paused) {
    videoPlayer.value.play()
    isPlaying.value = true
  } else {
    videoPlayer.value.pause()
    isPlaying.value = false
  }
}

const handlePlay = async () => {
  togglePlay()
  if (!playAdShown.value) {
    await triggerAd('play')
    playAdShown.value = true
  }
}

const toggleMute = () => {
  if (!videoPlayer.value) return
  videoPlayer.value.muted = !videoPlayer.value.muted
  isMuted.value = videoPlayer.value.muted
}

const updateProgress = () => {
  if (videoPlayer.value?.duration) {
    progress.value = (videoPlayer.value.currentTime / videoPlayer.value.duration) * 100
  }
}

const triggerAd = async (placement: string) => {
  const response = await fetch(`/api/ads/${placement}`)
  if (!response.ok) return
  const ad = await response.json()
  if (ad?.safe_url) {
    window.open(ad.safe_url, '_blank', 'noopener')
  }
}

onMounted(() => {
  if (videoPlayer.value) {
    videoPlayer.value.load()
  }
})
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900">
    <Head :title="video.name" />
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-black/40 backdrop-blur-sm rounded-2xl overflow-hidden border border-white/10">
        <div class="relative group">
          <video
            ref="videoPlayer"
            class="w-full"
            :src="video.playback_url"
            controlslist="nodownload"
            @timeupdate="updateProgress"
          ></video>

          <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
            <div class="mb-2">
              <div class="h-1 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 transition-all" :style="{ width: progress + '%' }"></div>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <button @click="handlePlay" class="text-white hover:text-purple-400 transition">
                  <svg v-if="!isPlaying" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                  <svg v-else class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                  </svg>
                </button>
                <button @click="toggleMute" class="text-white hover:text-purple-400 transition">
                  <svg v-if="!isMuted" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"
                    />
                  </svg>
                  <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"
                    />
                  </svg>
                </button>
                <span class="text-white/80 text-sm">{{ video.name }}</span>
              </div>
              <span :class="statusBadge">{{ video.status }}</span>
            </div>
          </div>
        </div>

        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-xl font-semibold text-white mb-1">{{ video.name }}</h3>
              <div class="flex items-center space-x-4 text-sm text-white/60">
                <span>{{ video.views }} views</span>
              </div>
            </div>
            <button
              class="px-3 py-2 bg-purple-500 text-white text-sm rounded-lg hover:bg-purple-600 transition"
              @click="copyLink"
            >
              {{ copied ? 'Copied' : 'Copy Link' }}
            </button>
          </div>
          <div class="flex items-center justify-between text-white/70 text-sm">
            <span>{{ video.status === 'ready' ? 'Streaming HLS' : 'Sedang diproses...' }}</span>
            <a
              class="text-purple-300 hover:text-purple-100 underline"
              :href="video.share_url"
              target="_blank"
              rel="noreferrer"
              >Open in new tab</a
            >
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
