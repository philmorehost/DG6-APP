package com.dgv6.app.util

import android.content.Context
import android.content.SharedPreferences

class PreferenceManager(context: Context) {
    private val prefs: SharedPreferences = context.getSharedPreferences(Constants.PREF_NAME, Context.MODE_PRIVATE)

    fun saveString(key: String, value: String) {
        prefs.edit().putString(key, value).apply()
    }

    fun getString(key: String, defaultValue: String = ""): String {
        return prefs.getString(key, defaultValue) ?: defaultValue
    }

    fun clear() {
        prefs.edit().clear().apply()
    }
}
