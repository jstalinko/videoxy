<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900">
    <!-- Header -->
    <header class="border-b border-white/10 backdrop-blur-sm bg-black/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="white" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
              </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Videy</h1>
          </div>
          <nav class="hidden md:flex items-center space-x-6">
            <a href="#" class="text-white/80 hover:text-white transition">Features</a>
            <a href="#" class="text-white/80 hover:text-white transition">Pricing</a>
            <a href="#" class="text-white/80 hover:text-white transition">About</a>
            <button class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition">
              Sign In
            </button>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center mb-12">
        <h2 class="text-5xl font-bold text-white mb-4">
          Share Your Videos
          <span class="block mt-2 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            Instantly
          </span>
        </h2>
        <p class="text-xl text-white/70 max-w-2xl mx-auto">
          Upload, share, and watch videos without limits. Fast, simple, and free.
        </p>
      </div>

      <!-- Upload Area -->
      <div v-if="!uploadedFile" class="max-w-3xl mx-auto">
        <div 
          @dragover="handleDragOver"
          @dragleave="handleDragLeave"
          @drop="handleDrop"
          :class="[
            'border-2 border-dashed rounded-2xl p-12 text-center transition-all',
            isDragging ? 'border-purple-400 bg-purple-500/10' : 'border-white/20 bg-white/5'
          ]"
        >
          <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mb-6">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-white mb-2">Drop your video here</h3>
            <p class="text-white/60 mb-6">or click to browse</p>
            <label class="cursor-pointer">
              <input 
                type="file" 
                accept="video/*" 
                class="hidden"
                @change="handleFileInput"
              />
              <span class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-lg transition inline-block">
                Select Video
              </span>
            </label>
            <p class="text-white/40 text-sm mt-6">Max file size: 500MB • Supports MP4, MOV, AVI, WebM</p>
          </div>
        </div>

        <!-- Features -->
        <div class="grid md:grid-cols-3 gap-6 mt-12">
          <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-white/10">
            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Lightning Fast</h3>
            <p class="text-white/60 text-sm">Upload and share your videos in seconds with our optimized servers.</p>
          </div>
          
          <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-white/10">
            <div class="w-12 h-12 bg-pink-500/20 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Secure & Private</h3>
            <p class="text-white/60 text-sm">Your videos are encrypted and protected with advanced security.</p>
          </div>
          
          <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-white/10">
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Easy Sharing</h3>
            <p class="text-white/60 text-sm">Get a shareable link instantly and reach anyone, anywhere.</p>
          </div>
        </div>
      </div>

      <!-- Video Player -->
      <div v-else class="max-w-4xl mx-auto">
        <div class="bg-black/40 backdrop-blur-sm rounded-2xl overflow-hidden border border-white/10">
          <div class="relative group">
            <video 
              ref="videoPlayer"
              class="w-full"
              :src="uploadedFile.url"
              @timeupdate="updateProgress"
            ></video>
            
            <!-- Video Controls -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
              <div class="mb-2">
                <div class="h-1 bg-white/20 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 transition-all" :style="{ width: progress + '%' }"></div>
                </div>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <button @click="togglePlay" class="text-white hover:text-purple-400 transition">
                    <svg v-if="!isPlaying" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                    <svg v-else class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                    </svg>
                  </button>
                  <button @click="toggleMute" class="text-white hover:text-purple-400 transition">
                    <svg v-if="!isMuted" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                    </svg>
                    <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                    </svg>
                  </button>
                  <span class="text-white/80 text-sm">{{ uploadedFile.name }}</span>
                </div>
                <div class="flex items-center space-x-2">
                  <button class="text-white hover:text-purple-400 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Video Info & Actions -->
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <div>
                <h3 class="text-xl font-semibold text-white mb-1">{{ uploadedFile.name }}</h3>
                <div class="flex items-center space-x-4 text-sm text-white/60">
                  <span>{{ uploadedFile.size }} MB</span>
                  <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ views }} views
                  </span>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
              <button class="flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                <span>Share</span>
              </button>
              
              <button class="flex items-center justify-center space-x-2 px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Download</span>
              </button>
              
              <button @click="uploadedFile = null" class="flex items-center justify-center space-x-2 px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>New Upload</span>
              </button>
            </div>

            <!-- Share Link -->
            <div class="mt-6 p-4 bg-white/5 rounded-lg border border-white/10">
              <p class="text-white/60 text-sm mb-2">Shareable Link</p>
              <div class="flex items-center space-x-2">
                <input 
                  type="text" 
                  readonly 
                  value="https://videy.co/v/abc123xyz"
                  class="flex-1 px-3 py-2 bg-black/20 text-white rounded border border-white/10 text-sm"
                />
                <button class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded transition text-sm">
                  Copy
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/10 backdrop-blur-sm bg-black/20 mt-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center text-white/60 text-sm">
          <p>© 2024 Videy. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'VideyClone',
  data() {
    return {
      isDragging: false,
      uploadedFile: null,
      isPlaying: false,
      isMuted: false,
      progress: 0,
      views: 1247
    }
  },
  methods: {
    handleDragOver(e) {
      e.preventDefault();
      this.isDragging = true;
    },
    handleDragLeave() {
      this.isDragging = false;
    },
    handleDrop(e) {
      e.preventDefault();
      this.isDragging = false;
      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith('video/')) {
        this.uploadedFile = {
          name: file.name,
          size: (file.size / (1024 * 1024)).toFixed(2),
          url: URL.createObjectURL(file)
        };
      }
    },
    handleFileInput(e) {
      const file = e.target.files[0];
      if (file && file.type.startsWith('video/')) {
        this.uploadedFile = {
          name: file.name,
          size: (file.size / (1024 * 1024)).toFixed(2),
          url: URL.createObjectURL(file)
        };
      }
    },
    togglePlay() {
      const video = this.$refs.videoPlayer;
      if (video.paused) {
        video.play();
        this.isPlaying = true;
      } else {
        video.pause();
        this.isPlaying = false;
      }
    },
    toggleMute() {
      const video = this.$refs.videoPlayer;
      video.muted = !video.muted;
      this.isMuted = video.muted;
    },
    updateProgress() {
      const video = this.$refs.videoPlayer;
      this.progress = (video.currentTime / video.duration) * 100;
    }
  }
}
</script>

<style scoped>
/* Tailwind CSS akan menangani semua styling */
</style>
