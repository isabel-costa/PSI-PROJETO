package com.example.ticketgomobileapp.adaptadores;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.ticketgomobileapp.R;
import com.example.ticketgomobileapp.modelos.Evento;

import java.util.List;

public class EventosAdapter extends RecyclerView.Adapter<EventosAdapter.EventoViewHolder> {

    private List<Evento> eventoList;

    public EventosAdapter(List<Evento> eventoList) {
        this.eventoList = eventoList;
    }

    @NonNull
    @Override
    public EventoViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_evento, parent, false);
        return new EventoViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull EventoViewHolder holder, int position) {
        Evento evento = eventoList.get(position);
        holder.tituloTextView.setText(evento.getTitulo());
        holder.descricaoTextView.setText(evento.getDescricao());
        holder.dataInicioTextView.setText(evento.getDataInicio());
        holder.dataFimTextView.setText(evento.getDataFim());
        holder.localNomeTextView.setText(evento.getLocalNome());
        holder.categoriaNomeTextView.setText(evento.getCategoriaNome());
    }

    @Override
    public int getItemCount() {
        return eventoList.size();
    }

    public void updateList(List<Evento> newList) {
        eventoList = newList;
        notifyDataSetChanged();
    }

    static class EventoViewHolder extends RecyclerView.ViewHolder {
        TextView tituloTextView;
        TextView descricaoTextView;
        TextView dataInicioTextView;
        TextView dataFimTextView;
        TextView localNomeTextView;
        TextView categoriaNomeTextView;

        public EventoViewHolder(@NonNull View itemView) {
            super(itemView);
            tituloTextView = itemView.findViewById(R.id.tituloTextView);
            descricaoTextView = itemView.findViewById(R.id.descricaoTextView);
            dataInicioTextView = itemView.findViewById(R.id.dataInicioTextView);
            dataFimTextView = itemView.findViewById(R.id.dataFimTextView);
            localNomeTextView = itemView.findViewById(R.id.localNomeTextView);
            categoriaNomeTextView = itemView.findViewById(R.id.categoriaNomeTextView);
        }
    }
}