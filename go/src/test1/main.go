package main

import (
	"fmt"
	"math"
	"time"
)

func sum(start, end float64, c chan float64) {
	pi := 0.0

	for i := start; i < end; i++ {
		pi = pi + math.Pow(-1, float64(int(i)%2)+1.0)/(2*i-1.0)
	}

	c <- pi
}

func main() {
	fmt.Println("START: ", time.Now())
	c := make(chan float64)
	pi := 0.0
	j := 100000.0

	fmt.Println(j * 5000)

	for i := 1.0; i < 5000; i++ { // 一共25 亿次
		go sum((i-1.0)*j, i*j, c)
	}

	for i := range c {
		pi = pi + i
	}
	fmt.Println(4 * pi)
	fmt.Println("END: ", time.Now())
}
