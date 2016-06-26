defmodule Genetic do
  import Genetic.Math

  def start(quantity, length) do

    population = create_population quantity, length

    IO.inspect population

    Genetic.Train.run population, &adaptation/1

  end

  def create_population(quantity, length) do

    for _ <- 1..quantity do
      for _ <- 1..length do
        if getRandomFloat > 0.5, do: 1, else: 0
      end
    end

  end

  defmodule Train do

    def run(population, adaptation, acc \\ 0) do

      IO.inspect population
      IO.inspect avg_adaptation population, adaptation

      cond do
        acc == 10 ->
          IO.inspect "stop"
          population

        adopted?(population, adaptation) ->
          IO.inspect "udało się"
          population

        true ->
          new_population = selection(population, adaptation)
            |> Genetic.Mutation.start

          run(new_population, adaptation, acc + 1)
      end

    end

    def avg_adaptation(population, adaptation) do
      sum = Enum.reduce(population, 0, fn ch, acc ->
        acc + adaptation.(ch)
      end)

      sum / Enum.count(population)
    end

    def adopted?(population, adaptation) do
      Enum.find(population, fn (ch) ->
        adaptation.(ch) == 31
      end)
    end

    defp selection(population, adaptation) do

      roulette_sections = Genetic.Roulette.run population, adaptation

      drawn_numbers = for _ <- 1..Enum.count(population) do
        getRandomFloat
      end

      Enum.map drawn_numbers, fn drawn_number ->

        index = Enum.find_index(roulette_sections, fn section ->
          {a, b} = section
          drawn_number >= a && drawn_number < b
        end)

        Enum.at population, index

      end

    end

  end

  defmodule Roulette do

    def run(population, adaptation) do
       results = roulette(population, population, adaptation, 0)
       {x, _} = List.last results
       List.update_at(results, Enum.count(results) - 1, fn _ -> {x, 1.1} end)
    end

    defp roulette(_, [], _, _) do
      []
    end

    defp roulette(population, [ch|tail], adaptation, previous) do
      current = previous + get_section(population, ch, adaptation)
      result = { previous, current }

      [ result | roulette(population, tail, adaptation, current ) ]
    end

    defp get_section(population, ch, adaptation) do
      all_adaptation = Enum.reduce(population, 0, fn (x, acc) ->
        acc + adaptation.(x)
      end)

      adaptation.(ch) / all_adaptation
    end

  end

  defmodule Mutation do

    def start(population) do

      crossed = Enum.flat_map( 1..round(Enum.count(population) / 2), fn i ->
        cross(Enum.at(population, i), Enum.at(population, i+1))
      end )

      Enum.map(crossed, &(mutate &1))

    end

    defp cross(ch1, ch2, p \\ 0.5) do

      if (Enum.count ch1) != (Enum.count ch2) do
        raise "chromosomy muszą być tej samej długości"
      end

      length = Enum.count ch1

      if (getRandomFloat < p) do

        crossPoint = round(getRandomFloat * (length - 2) + 1)

        {chn1a, chn2b} = Enum.split ch1, crossPoint
        {chn2a, chn1b} = Enum.split ch2, crossPoint

        [chn1a ++ chn1b, chn2a ++ chn2b]

      else

        [ch1, ch2]

      end

    end

    defp mutate(ch, p \\ 0.1) do

      Enum.map(ch, fn(x) ->
        if (getRandomFloat < p) do
          if x == 0, do: 1, else: 0
        else
          x
        end
      end)

    end

  end

  def adaptation(ch) do

    { value, _ } = Enum.map(ch, &(&1 + 48))
    |> List.to_string
    |> Integer.parse(2)

    value

  end

end
