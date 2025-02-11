package com.example.ticketgomobileapp.utils;

import android.content.Context;
import android.content.SharedPreferences;

public class AuthUtils {

    private static final String PREFS_NAME = "MyAppPrefs";
    private static final String KEY_IS_AUTHENTICATED = "is_authenticated";

    // Verificar se o usuário está autenticado
    public static boolean isUserAuthenticated(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getBoolean(KEY_IS_AUTHENTICATED, false); // Retorna 'false' se não houver informação sobre o usuário
    }

    // Salvar estado de autenticação
    public static void setUserAuthenticated(Context context, boolean isAuthenticated) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean(KEY_IS_AUTHENTICATED, isAuthenticated);
        editor.apply();
    }

    // Fazer logout (desmarcar o usuário como autenticado)
    public static void logout(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean(KEY_IS_AUTHENTICATED, false);  // Define como 'false' indicando que o usuário não está logado
        editor.apply();
    }
}
