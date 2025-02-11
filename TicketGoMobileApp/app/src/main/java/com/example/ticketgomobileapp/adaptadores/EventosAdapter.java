package com.example.ticketgomobileapp.adaptadores;

import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.ticketgomobileapp.DetalhesEventoActivity;
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
        holder.dataInicioTextView.setText(evento.getDataInicio());
        holder.localNomeTextView.setText(evento.getLocalNome());

        //carregar imagem com o glide
        Glide.with(holder.itemView.getContext())
                .load(evento.getImagemUrl())
                .into(holder.eventImage1);

        // Adicionar um listener de clique
        holder.itemView.setOnClickListener(v -> {
            Log.d("EventosAdapter", "Clicou no evento " + evento.getId());
            Intent intent = new Intent(holder.itemView.getContext(), DetalhesEventoActivity.class);
            intent.putExtra("evento_id", evento.getId()); // Passar o ID do evento
            holder.itemView.getContext().startActivity(intent);
        });
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
        TextView dataInicioTextView;
        TextView localNomeTextView;
        ImageView eventImage1;

        public EventoViewHolder(@NonNull View itemView) {
            super(itemView);
            tituloTextView = itemView.findViewById(R.id.tituloTextView);
            dataInicioTextView = itemView.findViewById(R.id.dataInicioTextView);
            localNomeTextView = itemView.findViewById(R.id.localNomeTextView);
            eventImage1 = itemView.findViewById(R.id.eventImage1);
        }
    }
}