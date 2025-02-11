package com.example.ticketgomobileapp.modelos;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import java.util.ArrayList;
import java.util.List;

public class FavoritoBDHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "dbEventos";
    private static final String FAVORITOS = "favoritos";
    private static final String ID = "id", PROFILE_ID = "profile_id", EVENTO_ID = "evento_id";

    private final SQLiteDatabase db;

    public FavoritoBDHelper(Context context) {
        super(context, DB_NAME, null, 1);
        this.db = getWritableDatabase();
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        String sqlCriarTabelaFavoritos = "CREATE TABLE " + FAVORITOS + " ( "
                + ID + " INTEGER PRIMARY KEY AUTOINCREMENT, "
                + PROFILE_ID + " INTEGER, "
                + EVENTO_ID + " INTEGER);";
        sqLiteDatabase.execSQL(sqlCriarTabelaFavoritos);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1) {
        String sqlDelTabelaFavoritos = "DROP TABLE IF EXISTS " + FAVORITOS;
        sqLiteDatabase.execSQL(sqlDelTabelaFavoritos);
        onCreate(sqLiteDatabase);
    }

    // Método para salvar um favorito no banco local
    public void guardarFavoritoBD(List<Favorito> favoritos) {
        SQLiteDatabase db = this.getWritableDatabase();
        db.beginTransaction();
        try {
            for (Favorito favorito : favoritos) {
                ContentValues values = new ContentValues();
                values.put("profile_id", favorito.getProfileId());
                values.put("evento_id", favorito.getEventoId());

                // Inserir o favorito na tabela
                db.insert("favoritos", null, values);
            }
            db.setTransactionSuccessful();
        } finally {
            db.endTransaction();
        }
    }


    // Método para pegar todos os eventos favoritos de um usuário
    public ArrayList<Integer> getFavoritosByProfile(int profileId) {
        ArrayList<Integer> favoritos = new ArrayList<>();
        Cursor cursor = this.db.query(FAVORITOS, new String[]{EVENTO_ID}, PROFILE_ID + " = ?", new String[]{String.valueOf(profileId)}, null, null, null);

        if (cursor.moveToFirst()) {
            do {
                favoritos.add(cursor.getInt(0)); // Evento ID
            } while (cursor.moveToNext());
            cursor.close();
        }

        return favoritos;
    }

    // Método para remover um favorito de um usuário
    public void removerFavoritoBD(int profileId, int eventoId) {
        this.db.delete(FAVORITOS, PROFILE_ID + " = ? AND " + EVENTO_ID + " = ?", new String[]{String.valueOf(profileId), String.valueOf(eventoId)});
    }
}
