package com.example.ticketgomobileapp.modelos;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class EventoBDHelper extends SQLiteOpenHelper {
    // Nome da base de dados
    private static final String DB_NAME = "dbEventos";
    // Nome da tabela de favoritos
    private static final String FAVORITOS = "favoritos";
    // Nome dos campos da tabela
    private static final String ID = "id", TITULO = "titulo", DESCRICAO = "descricao", DATA_INICIO = "datainicio", DATA_FIM = "datafim", LOCAL_NOME = "local_nome", CATEGORIA_NOME = "categoria_nome";
    // Criar conexão com a base de dados
    private final SQLiteDatabase db;

    public EventoBDHelper(@Nullable Context context) {
        super(context, DB_NAME, null, 1);
        // Permite ler e escrever
        this.db = getWritableDatabase();
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        String sqlCriarTabelaFavoritos = "CREATE TABLE " + FAVORITOS + " ( "
                + ID + " INTEGER PRIMARY KEY AUTOINCREMENT, "
                + TITULO + " TEXT NOT NULL, "
                + DESCRICAO + " TEXT NOT NULL, "
                + DATA_INICIO + " TEXT NOT NULL, "
                + DATA_FIM + " TEXT NOT NULL, "
                + LOCAL_NOME + " TEXT NOT NULL, "
                + CATEGORIA_NOME + " TEXT NOT NULL);";
        sqLiteDatabase.execSQL(sqlCriarTabelaFavoritos);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1) {
        String sqlDelTabelaFavoritos = "DROP TABLE IF EXISTS " + FAVORITOS;
        sqLiteDatabase.execSQL(sqlDelTabelaFavoritos);
        onCreate(sqLiteDatabase);
    }

    // Métodos CRUD para favoritos
    /*public ArrayList<Evento> getAllFavoritosBD() {
        ArrayList<Evento> favoritos = new ArrayList<>();
        Cursor cursor = this.db.query(FAVORITOS, new String[]{ID, TITULO, DESCRICAO, DATA_INICIO, DATA_FIM, LOCAL_NOME, CATEGORIA_NOME}, null, null, null, null, null);

        if (cursor.moveToFirst()) {
            do {
                Evento auxEvento = new Evento(cursor.getInt(0), cursor.getString(1), cursor.getString(2), cursor.getString(3), cursor.getString(4), cursor.getString(5), cursor.getString(6));
                favoritos.add(auxEvento);
            } while (cursor.moveToNext());
            cursor.close();
        }

        return favoritos;
    }*/

    /*public Evento getEventoById(int eventoId) {
        Evento evento = null;
        Cursor cursor = this.db.query(
                "eventos", // Nome da tabela de eventos
                new String[]{ID, TITULO, DESCRICAO, DATA_INICIO, DATA_FIM, LOCAL_NOME, CATEGORIA_NOME},
                ID + " = ?",
                new String[]{String.valueOf(eventoId)},
                null, null, null
        );

        if (cursor != null && cursor.moveToFirst()) {
            evento = new Evento(
                    cursor.getInt(0), // ID
                    cursor.getString(1), // Título
                    cursor.getString(2), // Descrição
                    cursor.getString(3), // Data Início
                    cursor.getString(4), // Data Fim
                    cursor.getString(5), // Local
                    cursor.getString(6)  // Categoria
            );
            cursor.close();
        }

        return evento;
    }*/
}