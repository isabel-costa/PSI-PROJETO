// build.gradle.kts (Project-level)
plugins {
    id("com.android.application") version "8.7.2" apply false
    id("org.jetbrains.kotlin.android") version "1.8.10" apply false
}


buildscript {
    repositories {
        google()
        mavenCentral()
    }
    dependencies {
        // Change the version of the Android Gradle Plugin to 8.7.2
        classpath("com.android.tools.build:gradle:8.7.2")
    }
}
